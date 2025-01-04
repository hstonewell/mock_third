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
    public $successMessage;
    public $errorMessage;

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
            'subject' => 'required|max:191',
            'content' => 'required'
        ]);

        try {
            Mail::to($this->email)->send(new Contact(
                $this->subject,
                $this->content,
                $this->userName
            ));
            $this->closeModal();
            return redirect(route('admin.index'))->with('success', 'メールを送信しました');
        } catch (\Exception $e) {
            $this->closeModal();
            return redirect(route('admin.index'))->with('error', 'メール送信に失敗しました');
        }
    }

    public function resetFields()
    {
        $this->subject = '';
        $this->content = '';
    }
}
