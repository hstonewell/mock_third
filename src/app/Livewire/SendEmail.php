<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use App\Mail\Contact;

class SendEmail extends Component
{
    public $showModal = false;
    public $userName = '';
    public $email = '';
    public $subject = '';
    public $content = '';

    public function mount($email = '', $userName = '')
    {
        $this->email = $email;
        $this->userName = $userName;
    }

    public function render()
    {
        return view('livewire.send-email');
    }

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal =false;
        $this->reset(['subject', 'content']);
    }

    public function send()
    {
        $this->validate([
            'subject' => 'required|max:255',
            'content' => 'required'
        ]);

        try {
            Mail::to($this->email)->send(new Contact(
                $this->subject,
                $this->content,
                $this->userName
            ));

            // 成功時の処理
            session()->flash('success', 'メールを送信しました');
            $this->closeModal();
        } catch (\Exception $e) {
            // エラー時の処理
            session()->flash('error', 'メール送信に失敗しました');
        }
    }
}
