<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $fillable = [
        'category_id',
        'room_id',
        'name',
        'location',
        'condition',
        'description',
        'photo',
        'capacity',
        'unit',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function repairReports()
    {
        return $this->hasMany(RepairReport::class);
    }

    /**
     * Basis ketersediaan: unit atau capacity dari kategori (default unit).
     */
    public function getAvailabilityLabelAttribute(): string
    {
        return $this->category?->type ?? 'unit';
    }

    /**
     * Nilai ketersediaan sesuai basis.
     */
    public function getAvailabilityLimitAttribute(): int
    {
        $basis = $this->availability_label;
        $value = $basis === 'capacity' ? $this->capacity : $this->unit;
        return $value && $value > 0 ? $value : 1;
    }

    /**
     * Batas maksimum ketersediaan (ambil nilai terbesar antara unit & capacity).
     */
    public function getMaxAvailabilityAttribute(): int
    {
        // Mengikuti basis yang dipilih oleh kategori
        return $this->availability_limit;
    }

    /**
     * Sisa unit saat ini berdasarkan booking yang sedang berlangsung (approved/active).
     */
    public function getAvailableStockNowAttribute(): int
    {
        $max = $this->max_availability;
        $now = now();

        $used = $this->bookings()
            ->where('status', 'active') // hitung hanya yang sudah check-in
            ->where('end_time', '>=', $now) // tetap terhitung sampai selesai, supaya stok kembali setelah end_time lewat
            ->sum('capacity_used');

        $remaining = $max - $used;

        return $remaining > 0 ? $remaining : 0;
    }

    /**
     * Apakah unit sudah habis pada waktu sekarang.
     */
    public function getInUseNowAttribute(): bool
    {
        return $this->available_stock_now <= 0;
    }

    /**
     * Alias: simpan unit (fallback dibaca dari kolom lama 'stock' jika belum migrasi).
     */
    public function setUnitAttribute($value): void
    {
        // Kolom utama sekarang 'unit' (fallback ke 'stock' jika belum migrasi)
        $this->attributes['unit'] = $value;
    }

    /**
     * Alias: ambil unit, fallback ke kolom 'stock' jika masih ada.
     */
    public function getUnitAttribute()
    {
        return $this->attributes['unit'] ?? $this->attributes['stock'] ?? null;
    }
}
