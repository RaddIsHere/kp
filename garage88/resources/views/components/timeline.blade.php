@props(['progress', 'highlight' => false])
<ul class="timeline">
    @forelse($progress as $p)
        <li class="{{ $loop->first ? 'timeline-current' : '' }} {{ $loop->first && $highlight ? 'timeline-highlight' : '' }}">
            <div class="fw-semibold">{{ $p->status }}</div>
            @if($p->keterangan)<div class="text-secondary small">{{ $p->keterangan }}</div>@endif
            <div class="text-secondary small"><i class="bi bi-clock"></i> {{ $p->tanggal_update->translatedFormat('d M Y, H:i') }}</div>
        </li>
    @empty
        <li class="text-secondary">Belum ada progress.</li>
    @endforelse
</ul>
