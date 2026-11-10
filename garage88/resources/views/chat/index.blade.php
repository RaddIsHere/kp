@extends('layouts.app')
@section('title', 'Chat Admin')
@push('styles')
<style>
    .chat-shell { display: grid; grid-template-columns: 260px minmax(0, 1fr); max-width: 1180px; min-height: 620px; margin: 0 auto; overflow: hidden; border: 1px solid #cbd5e1; border-radius: 16px; background: rgba(255,255,255,.78); box-shadow: 0 18px 36px rgba(15,23,42,.08); }
    .chat-list { border-right: 1px solid #dbe3ed; background: #f3f6fa; }
    .chat-list h1 { margin: 0; padding: 1.15rem; color: #17283e; font-size: 1.15rem; }
    .chat-contact { display: flex; justify-content: space-between; gap: .7rem; padding: .85rem 1rem; border-top: 1px solid #dbe3ed; color: #334155; text-decoration: none; }
    .chat-contact:hover, .chat-contact.active { background: #e5eefb; color: #173d80; }
    .chat-contact small { color: #718096; }
    .chat-main { display: flex; min-width: 0; flex-direction: column; }
    .chat-heading { padding: 1rem 1.25rem; border-bottom: 1px solid #dbe3ed; color: #17283e; font-weight: 750; }
    .chat-messages { display: flex; flex: 1; flex-direction: column; gap: .75rem; overflow-y: auto; padding: 1.25rem; }
    .chat-message { max-width: min(78%, 620px); align-self: flex-start; padding: .7rem .9rem; border: 1px solid #d7e0eb; border-radius: 12px 12px 12px 3px; background: #fff; color: #24344a; overflow-wrap: anywhere; }
    .chat-message.mine { align-self: flex-end; border-color: #bfd2f5; border-radius: 12px 12px 3px 12px; background: #eaf1ff; }
    .chat-message small { display: block; margin-top: .35rem; color: #728198; font-size: .68rem; }
    .chat-compose { display: flex; gap: .65rem; padding: 1rem; border-top: 1px solid #dbe3ed; }
    .chat-compose textarea { min-height: 46px; max-height: 130px; resize: vertical; }
    .chat-empty { display: grid; flex: 1; place-content: center; gap: .45rem; padding: 2rem; color: #63748b; text-align: center; }
    .chat-empty i { color: #507bb8; font-size: 2rem; }
    @media (max-width: 767.98px) { .chat-shell { grid-template-columns: 1fr; min-height: calc(100vh - 200px); } .chat-list { max-height: 190px; overflow-y: auto; border-right: 0; border-bottom: 1px solid #dbe3ed; } .chat-list h1 { padding: .75rem 1rem; } .chat-contact { padding: .6rem 1rem; } .chat-message { max-width: 90%; } }
</style>
@endpush
@section('content')
<section class="chat-shell" aria-label="Percakapan dengan admin">
    @if($isAdmin)
        <aside class="chat-list">
            <h1>Pesan pelanggan</h1>
            @forelse($customers as $customer)
                <a class="chat-contact {{ $pelanggan?->id === $customer->id ? 'active' : '' }}" href="{{ route('admin.chat.show', $customer) }}">
                    <span>{{ $customer->user->name }}<br><small>{{ $customer->chat_messages_count }} pesan</small></span>
                    <i class="bi bi-chevron-right" aria-hidden="true"></i>
                </a>
            @empty
                <p class="px-3 text-secondary small">Belum ada pelanggan.</p>
            @endforelse
        </aside>
    @endif
    <div class="chat-main">
        <div class="chat-heading">{{ $isAdmin ? ($pelanggan?->user->name ?? 'Pilih pelanggan') : 'Chat dengan Admin' }}</div>
        @if($pelanggan)
            <div class="chat-messages">
                @forelse($messages as $message)
                    <article class="chat-message {{ $message->sender_id === auth()->id() ? 'mine' : '' }}">
                        {{ $message->message }}
                        <small>{{ $message->sender->name }} · {{ $message->created_at->format('d/m/Y H:i') }}</small>
                    </article>
                @empty
                    <div class="chat-empty"><i class="bi bi-chat-dots"></i><strong>Belum ada pesan</strong><span>Kirim pesan untuk memulai percakapan.</span></div>
                @endforelse
            </div>
            <form class="chat-compose" method="POST" action="{{ $isAdmin ? route('admin.chat.store', $pelanggan) : route('pelanggan.chat.store') }}">
                @csrf
                <textarea class="form-control" name="message" maxlength="2000" rows="1" placeholder="Tulis pesan..." required>{{ old('message') }}</textarea>
                <button class="btn btn-accent" type="submit" aria-label="Kirim pesan" title="Kirim pesan"><i class="bi bi-send"></i></button>
            </form>
        @else
            <div class="chat-empty"><i class="bi bi-chat-dots"></i><strong>Percakapan admin dan pelanggan</strong><span>Pilih pelanggan di samping untuk melihat pesan.</span></div>
        @endif
    </div>
</section>
@endsection