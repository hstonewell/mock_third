<div>
    <form method="post" action="{{ route('profile.create') }}" class="edit-form__input" method="post" enctype="multipart/form-data" wire:submit.prevent="save">
        @csrf
        <div class="edit-form__image">
            <img src="{{ $profileImage ? $profileImage->temporaryUrl() : $profileImageUrl }}" class="profile-thumbnail">
            <label for="image" class="edit-button">画像を選択する</label>
            <input type="file" name="image" id="image" wire:model="profileImage" accept="image/*" hidden />
            @if ($errors->has('image'))
            @foreach($errors->get('image') as $message)
            <p class="form--error-message">
                {{ $message }}
            </p>
            @endforeach
            @endif
        </div>
        <div class="edit-form__unit">
            <label class="edit-form__input--label">ユーザー名</label>
            <input class="edit-form__input--input" type="text" name="name" value="{{ old('name', $name ?? '') }}" wire:model="name" />
            @if ($errors->has('name'))
            @foreach($errors->get('name') as $message)
            <p class="form--error-message">
                {{ $message }}
            </p>
            @endforeach
            @endif
        </div>
        <div class="edit-form__unit">
            <label class="edit-form__input--label">郵便番号</label>
            <input class="edit-form__input--input" type="text" name="postcode" wire:model="postcode" />
            @if ($errors->has('postcode'))
            @foreach($errors->get('postcode') as $message)
            <p class="form--error-message">
                {{ $message }}
            </p>
            @endforeach
            @endif
        </div>
        <div class="edit-form__unit">
            <label class="edit-form__input--label">住所</label>
            <input class="edit-form__input--input" type="text" name="address" wire:model="address" />
            @if ($errors->has('address'))
            @foreach($errors->get('address') as $message)
            <p class="form--error-message">
                {{ $message }}
            </p>
            @endforeach
            @endif
        </div>
        <div class="edit-form__unit">
            <label class="edit-form__input--label">建物名</label>
            <input class="edit-form__input--input" type="text" name="building" wire:model="building" />
            @if ($errors->has('building'))
            @foreach($errors->get('building') as $message)
            <p class="form--error-message">
                {{ $message }}
            </p>
            @endforeach
            @endif
        </div>
        <button type="submit" class="submit-button">更新する</button>
    </form>
</div>