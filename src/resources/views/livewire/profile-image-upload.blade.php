<div>
    <form class="edit-form__image" method="post" enctype="multipart/form-data" wire:submit.prevent="save">
        <img src="{{ $imageUrl }}" class="profile-thumbnail">
        <label for="image" class="edit-button">画像を選択する</label>
        <input type="file" name="image" id="image" wire:model="image" accept="image/*" hidden />
    </form>
</div>