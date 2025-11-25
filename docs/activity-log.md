# Activity Log Implementation Guide

This document menjabarkan pondasi fitur activity log yang sudah disiapkan ketua tim serta detail yang dibutuhkan anggota lain untuk menyelesaikan UI dan integrasi event.

## 1. Skema Data

Tabel `activity_logs` memiliki kolom:

| Kolom | Tipe | Keterangan |
| --- | --- | --- |
| `id` | bigint | Primary key |
| `user_id` | bigint nullable | User pelaku, `NULL` jika sistem |
| `action` | varchar(120) | Identifier event, gunakan format `domain.action` |
| `description` | text | Ringkasan human-readable |
| `detail` | json nullable | Payload tambahan (array) |
| `context` | varchar nullable | Modul/konteks mis. `posts`, `reports` |
| `ip_address` | varchar(45) nullable | IP terakhir |
| `user_agent` | varchar nullable | Agent terakhir |
| `created_at` | timestamp | Timestamp ditampilkan di UI |

Relasi:
- `ActivityLog` belongsTo `User`.
- `User` hasMany `ActivityLog`.

Helper service: `App\Services\ActivityLogService` dengan method `log($actor, $action, $description, $detail = null, $context = [])`. Actor boleh berupa instance `User`, user id (int), atau `null` (sistem). Detail otomatis dinormalisasi menjadi array agar valid JSON. Konteks opsional (`['name' => 'posts']`, `['ip' => '1.1.1.1']`, dll).

## 2. Event Matrix

| Action | Trigger | Deskripsi | Detail Minimal |
| --- | --- | --- | --- |
| `auth.login` | Login sukses | User berhasil login | `{ email, method }` |
| `auth.logout` | Logout | User keluar dari sistem | `{ via }` |
| `post.created` | Buat post | Post baru dibuat | `{ post_id, title }` |
| `post.updated` | Update post | Post diperbarui | `{ post_id, changes }` |
| `post.deleted` | Hapus post | Post dihapus/soft delete | `{ post_id }` |
| `comment.created` | Tambah komentar | Komentar baru | `{ comment_id, post_id }` |
| `comment.deleted` | Hapus komentar | Komentar dihapus | `{ comment_id }` |
| `report.submitted` | User lapor | Report dibuat | `{ report_id, target_type }` |
| `report.status_changed` | Admin ubah status | Status report berubah | `{ report_id, from, to }` |
| `profile.updated` | Update profil | User memperbarui profil | `{ fields }` |
| `reaction.added` | Like/Dislike | User memberi reaksi | `{ post_id, reaction }` |

> Catatan: anggota Backend wajib memanggil `ActivityLogService` pada titik-titik di atas. Jika ada domain tambahan (FAQ, kategori), tambahkan action baru dengan format serupa.

## 3. API / Route Kontrak

Semua route berada di area admin (middleware `auth` + `IsAdmin`), agar hanya admin yang bisa melihat log.

### 3.1 List Logs
- **Route**: `GET /admin/activity-logs`
- **Query**:
  - `user` (optional, int) – filter berdasarkan user_id
  - `action` (optional, string) – filter prefix action (mis. `post.`)
  - `search` (optional, string) – cari di `description`
  - `date_from`, `date_to` (optional, date) – rentang tanggal
  - `per_page` (optional, default 20)
- **Response JSON (untuk UI front-end / AJAX)**:

```json
{
  "data": [
    {
      "id": 1,
      "user": {"id": 9, "name": "Admin"},
      "action": "post.created",
      "description": "Admin membuat post 'Hello World'",
      "context": "posts",
      "detail": {"post_id": 32, "title": "Hello World"},
      "ip_address": "127.0.0.1",
      "timestamp": "2025-11-25T08:35:00+07:00"
    }
  ],
  "meta": {
    "current_page": 1,
    "per_page": 20,
    "total": 45
  }
}
```

Backend dev bertanggung jawab mengisi endpoint JSON + view composer (Blade) supaya halaman admin bisa dirender server-side jika dibutuhkan.

### 3.2 Detail Log
- **Route**: `GET /admin/activity-logs/{log}`
- **Response**: detail satu log + payload `detail`. Jika tidak menggunakan halaman terpisah, cukup sediakan endpoint JSON untuk modal detail.

## 4. Alur Pengembangan

1. **Ketua (done di branch ini)**  
   - Migration + model + service + dokumentasi.
2. **Backend**  
   - Implementasi endpoint list/detail sesuai kontrak di atas.  
   - Tambahkan pemanggilan service di setiap event (matrix).
3. **Frontend/UI**  
   - Buat halaman Blade `resources/views/admin/activity-logs/index.blade.php` berisi tabel.  
   - Gunakan datatable/pagination standar Laravel.  
   - Tambahkan modal/detail viewer.
4. **QA/Docs**  
   - Seed sample log + manual test checklist.  
   - Update README cara menggunakan & menambah event.

Semua PR wajib diarahkan ke branch `feature/activity-log` dan direview silang minimal satu orang sebelum merge.

