<div>
    <form action="{{ route('sell.create') }}" method="post" class="edit-form__input" id="sell" enctype="multipart/form-data" wire:submit.prevent="save">
        <label for="item-image" class="edit-form__input--label">商品画像</label>
        <div class="edit-form__item-image">
            @if($itemImage)
            <div class="edit-form__image-container">
                <img src="{{ $itemImage->temporaryUrl() }}" class="edit-form__preview-image">
                <button wire:click="closePreviewImage()" type="button" class="edit-form--close-button"><i class="fa-regular fa-circle-xmark fa-xl" style="color: #5f5f5f;"></i></button>
            </div>
            @else
            <label for="itemImage" class="edit-button" id="item-image">画像を選択する</label>
            <input type="file" name="itemImage" id="itemImage" wire:model="itemImage" accept="image/*" hidden />
            @endif
        </div>
        @error('itemImage')
        <p class="form--error-message">
            {{ $message }}
        </p>
        @enderror
        <div class="edit-form__unit">
            <h2 class="sell-item__form--title">商品の詳細</h2>
            <label for="brandName" class="edit-form__input--label">ブランド名</label>
            <input type="text" name="brandName" wire:model="brandName" class="edit-form__input--input" />
            <label for="categoryId" class="edit-form__input--label">カテゴリー</label>
            <select name="categoryId" class="edit-form__input--select" wire:model="categoryId">
                <option value="">選択してください</option>
                @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                @if ($category->children->isNotEmpty())
                @foreach ($category->children as $child)
                <option value="{{ $child->id }}">-- {{ $child->category_name }}</option>
                @endforeach
                @endif
                @endforeach
            </select>
            <label for="conditionId" class="edit-form__input--label">商品の状態</label>
            <select name="conditionId" class="edit-form__input--select" wire:model="conditionId">
                <option value="">選択してください</option>
                @foreach ($conditions as $condition)
                <option value="{{ $condition->id }}">{{ $condition->condition }}</option>
                @endforeach
            </select>
        </div>
        <div class="edit-form__unit">
            <h2 class="sell-item__form--title">商品名と説明</h2>
            <label for="item_name" class="edit-form__input--label">商品名</label>
            <input type="text" wire:model="item_name" name="item_name" class="edit-form__input--input" />
            @error('item_name')
            <p class="form--error-message">
                {{ $message }}
            </p>
            @enderror
            <label for="description" class="edit-form__input--label">商品の説明</label>
            <textarea wire:model="description" name="description" rows="5" class="edit-form__input--textarea"></textarea>
            @error('description')
            <p class="form--error-message">
                {{ $message }}
            </p>
            @enderror
        </div>
        <div class="edit-form__unit">
            <h2 class="sell-item__form--title">販売価格</h2>
            <label for="price" class="edit-form__input--label">販売価格</label>
            <div class="edit-form__input--input" id="price">
                <span class="edit-form__input--prefix">&yen;</span>
                <input type="text" name="price" wire:model.lazy="price" />
            </div>
            @error('price')
            <p class="form--error-message">
                {{ $message }}
            </p>
            @enderror
        </div>
        <button type="submit" class="submit-button">出品する</button>
    </form>
</div>