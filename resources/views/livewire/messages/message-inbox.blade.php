<div>
  <!-- Page Header -->
  <div class="page-header">
    <div>
      <h1 class="page-title">Pesan & Pokok Doa Jemaat</h1>
      <p class="page-subtitle">Kotak masuk permohonan doa, konseling, dan pesan yang dikirimkan jemaat melalui Portal QR</p>
    </div>
    @if ($unreadCount > 0)
      <span class="badge bg-danger fs-6 px-3 py-2 rounded-pill">
        <i class="bi bi-bell-fill me-1"></i> {{ $unreadCount }} Pesan Belum Dibaca
      </span>
    @endif
  </div>

  <!-- Filter Toolbar -->
  <div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
      <div class="row g-3 align-items-center">
        <div class="col-12 col-md-5">
          <div class="input-group">
            <span class="input-group-text bg-light"><i class="bi bi-search text-muted"></i></span>
            <input type="text" class="form-control" wire:model.live.debounce.300ms="search"
              placeholder="Cari dalam isi pesan atau nama...">
          </div>
        </div>
        <div class="col-12 col-sm-6 col-md-4">
          <select class="form-select" wire:model.live="coolFilter">
            <option value="">Semua Kelompok COOL</option>
            @foreach ($cools as $c)
              <option value="{{ $c->cool_id }}">{{ $c->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
          <select class="form-select" wire:model.live="statusFilter">
            <option value="">Semua Status Pesan</option>
            <option value="SENT">Belum Dibaca (Baru)</option>
            <option value="READ">Sudah Dibaca</option>
            <option value="RESPONDED">Sudah Direspon</option>
          </select>
        </div>
      </div>
    </div>
  </div>

  <!-- Messages List -->
  <div class="card shadow-sm border-0">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>Pengirim & Kelompok</th>
              <th style="width: 45%;">Isi Pesan / Pokok Doa</th>
              <th>Waktu Masuk</th>
              <th>Status</th>
              <th class="text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($messages as $msg)
              @php
                // Extract phone from message if any
                $phone = null;
                if (preg_match('/\((\+?[0-9\s-]+)\)/', $msg->message, $matches)) {
                    $phone = $matches[1];
                } elseif ($msg->member && $msg->member->phone) {
                    $phone = $msg->member->phone;
                }
              @endphp
              <tr wire:key="msg-row-{{ $msg->message_id }}" class="{{ $msg->status === 'SENT' ? 'table-warning bg-opacity-25' : '' }}">
                <td>
                  <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 38px; height: 38px; font-weight: 600;">
                      <i class="bi bi-chat-left-quote-fill"></i>
                    </div>
                    <div>
                      <div class="fw-bold text-dark">
                        {{ $msg->member->name ?? 'Jemaat COOL' }}
                      </div>
                      <span class="badge bg-light text-dark border">{{ $msg->cool->name ?? '-' }}</span>
                    </div>
                  </div>
                </td>
                <td>
                  <div class="p-2 bg-light rounded text-secondary" style="white-space: pre-line; font-size: 0.9rem;">
                    {{ $msg->message }}
                  </div>
                </td>
                <td>
                  <span class="text-muted small">
                    <i class="bi bi-clock me-1"></i>
                    {{ $msg->date_created ? $msg->date_created->format('d M Y H:i') : '-' }}
                  </span>
                </td>
                <td>
                  @if ($msg->status === 'SENT')
                    <span class="badge bg-warning text-dark">Belum Dibaca</span>
                  @elseif ($msg->status === 'READ')
                    <span class="badge bg-info text-dark">Sudah Dibaca</span>
                  @elseif ($msg->status === 'RESPONDED')
                    <span class="badge bg-success">Sudah Direspon</span>
                  @else
                    <span class="badge bg-secondary">{{ $msg->status }}</span>
                  @endif
                </td>
                <td class="text-end text-nowrap">
                  @if ($phone)
                    @php
                      $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
                      if (str_starts_with($cleanPhone, '0')) {
                          $cleanPhone = '62' . substr($cleanPhone, 1);
                      }
                      $waReply = "Shalom, terima kasih telah mengirimkan pokok doa / pesan melalui Portal COOL. Kami mendoakan pokok doa ini...";
                    @endphp
                    <a href="https://wa.me/{{ $cleanPhone }}?text={{ urlencode($waReply) }}" target="_blank"
                      class="btn btn-sm btn-outline-success me-1" title="Balas via WhatsApp">
                      <i class="bi bi-whatsapp"></i>
                    </a>
                  @endif

                  @if ($msg->status === 'SENT')
                    <button type="button" class="btn btn-sm btn-outline-primary me-1" wire:click="markAsRead({{ $msg->message_id }})" title="Tandai Sudah Dibaca">
                      <i class="bi bi-check2"></i>
                    </button>
                  @endif

                  @if ($msg->status !== 'RESPONDED')
                    <button type="button" class="btn btn-sm btn-outline-success me-1" wire:click="markAsResponded({{ $msg->message_id }})" title="Tandai Sudah Direspon">
                      <i class="bi bi-check2-all"></i>
                    </button>
                  @endif

                  <button type="button" class="btn btn-sm btn-outline-danger"
                    onclick="if(confirm('Hapus pesan ini?')) { @this.call('deleteMessage', {{ $msg->message_id }}) }"
                    title="Hapus">
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center text-muted py-5">
                  <i class="bi bi-chat-square-dots fs-1 d-block mb-2 text-secondary"></i>
                  Tidak ada pesan masuk dari jemaat.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    @if ($messages->hasPages())
      <div class="card-footer bg-white border-0 py-3">
        {{ $messages->links() }}
      </div>
    @endif
  </div>
</div>
