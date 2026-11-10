<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Pelanggan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function customerIndex(Request $request)
    {
        $pelanggan = $request->user()->pelanggan()->firstOrCreate([]);

        return $this->conversation($pelanggan, false);
    }

    public function customerStore(Request $request): RedirectResponse
    {
        $pelanggan = $request->user()->pelanggan()->firstOrCreate([]);
        $this->saveMessage($request, $pelanggan);

        return redirect()->route('pelanggan.chat');
    }

    public function adminIndex(?Pelanggan $pelanggan = null)
    {
        $customers = Pelanggan::with('user')->withCount('chatMessages')->orderByDesc('id')->get();

        return $this->conversation($pelanggan, true, $customers);
    }

    public function adminStore(Request $request, Pelanggan $pelanggan): RedirectResponse
    {
        $this->saveMessage($request, $pelanggan);

        return redirect()->route('admin.chat.show', $pelanggan);
    }

    private function conversation(?Pelanggan $pelanggan, bool $isAdmin, $customers = null)
    {
        $messages = $pelanggan
            ? ChatMessage::with('sender')->where('pelanggan_id', $pelanggan->id)->latest()->take(100)->get()->reverse()->values()
            : collect();

        return view('chat.index', compact('pelanggan', 'customers', 'messages', 'isAdmin'));
    }

    private function saveMessage(Request $request, Pelanggan $pelanggan): void
    {
        $data = $request->validate(['message' => ['required', 'string', 'max:2000']]);

        ChatMessage::create([
            'pelanggan_id' => $pelanggan->id,
            'sender_id' => $request->user()->id,
            'message' => $data['message'],
        ]);
    }
}