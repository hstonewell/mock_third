<div>
    <h5 class="edit-form__label">商品画像</h5>
    <form class="edit-form__item-image" method="post" enctype="multipart/form-data" wire:submit.prevent="save">
        @if($itemImage)
        <div class="edit-form__image-container">
            <img src="{{ $itemImage->temporaryUrl() }}" class="edit-form__preview-image">
            <button wire:click="closePreviewImage()" type="button" class="edit-form--close-button"><i class="fa-regular fa-circle-xmark fa-xl" style="color: #5f5f5f;"></i></button>
        </div>
        @else
        <label for="itemImage" class="edit-button" id="item-image">画像を選択する</label>
        <input type="file" name="itemImage" id="itemImage" wire:model="itemImage" accept="image/*" hidden />
        @endif
    </form>
</div>