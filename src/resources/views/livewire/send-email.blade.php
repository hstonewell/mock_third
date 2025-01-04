<div>
    <button wire:click="openModal()" type="button" class="email-button">
        <i class="fa-solid fa-envelope fa-2xl" style="color: #5f5f5f"></i>
    </button>
    @if($showModal)
    <div class="mail-form">
        <div class="mail-form__inner">
            <div class="mail-form--close-button">
                <button wire:click="closeModal()" type="button">
                    <i class="fa-regular fa-circle-xmark fa-xl" style="color: #5f5f5f;"></i>
                </button>
            </div>
            <form wire:submit.prevent="send">
                <label for="to" class="edit-form__input--label">宛先</label>
                <input class="mail-form__email" name="to" value="{{ $email }}" readonly>
                <label for="subject" class="edit-form__input--label">件名</label>
                <input type="text" name="subject" wire:model="subject" class="edit-form__input--input" />
                @error('subject')
                <p class="form--error-message">
                    {{ $message }}
                </p>
                @enderror
                <label for="content" class="edit-form__input--label">内容</label>
                <textarea wire:model="content" name="content" rows="5" class="edit-form__input--textarea"></textarea>
                @error('content')
                <p class="form--error-message">
                    {{ $message }}
                </p>
                @enderror
                <button type="submit" class="submit-button" id="send-email">送信する</button>
            </form>
        </div>
    </div>
    @endif
</div>