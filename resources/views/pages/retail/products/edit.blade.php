@extends('layouts.admin')

@php
    $hideTopbarTitle = true;
@endphp

@section('content')
    <div class="retail-form-container">
        <div class="retail-form-card">
            <div class="retail-form-header mb-4">
                <h1 class="retail-form-title">Edit Product</h1>
                <p class="retail-form-subtitle">Update product details and photo</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('retail.products.update', $product->id) }}" enctype="multipart/form-data" class="retail-form">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <!-- Image Upload Section -->
                    <div class="form-section form-section-full">
                        <label class="form-label-main">Product Photo</label>
                        <div class="image-upload-wrapper">
                            <input type="file" id="imageInput" name="image" class="image-input" accept="image/*">
                            
                            @if ($product->image)
                                <div class="image-preview-wrapper" id="imagePreviewWrapper">
                                    <img id="imagePreview" class="image-preview" src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}">
                                    <button type="button" class="btn-remove-image" onclick="removeImage(event)">
                                        <i class="bi bi-x-circle-fill"></i>
                                    </button>
                                </div>
                                <div class="image-upload-area" id="imageUploadArea" style="display: none;" onclick="document.getElementById('imageInput').click()">
                                    <div class="upload-icon">
                                        <i class="bi bi-cloud-arrow-up"></i>
                                    </div>
                                    <div class="upload-text">
                                        <p class="upload-main-text">Click to upload or drag and drop</p>
                                        <p class="upload-sub-text">PNG, JPG, WebP up to 5MB</p>
                                    </div>
                                </div>
                            @else
                                <div class="image-upload-area" id="imageUploadArea" onclick="document.getElementById('imageInput').click()">
                                    <div class="upload-icon">
                                        <i class="bi bi-cloud-arrow-up"></i>
                                    </div>
                                    <div class="upload-text">
                                        <p class="upload-main-text">Click to upload or drag and drop</p>
                                        <p class="upload-sub-text">PNG, JPG, WebP up to 5MB</p>
                                    </div>
                                </div>
                                <div class="image-preview-wrapper" id="imagePreviewWrapper" style="display: none;">
                                    <img id="imagePreview" class="image-preview" alt="Preview">
                                    <button type="button" class="btn-remove-image" onclick="removeImage(event)">
                                        <i class="bi bi-x-circle-fill"></i>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Product Information Grid -->
                    <div class="form-section form-section-full">
                        <label class="form-label-main">Product Information</label>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Product Name *</label>
                                <input 
                                    type="text" 
                                    name="name" 
                                    class="form-control @error('name') is-invalid @enderror" 
                                    value="{{ old('name', $product->name) }}" 
                                    placeholder="e.g., Premium Hog Feed"
                                    required
                                >
                                @error('name')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Category *</label>
                                <div class="custom-select-wrapper">
                                    <select name="category" class="form-select custom-select @error('category') is-invalid @enderror" required>
                                        <option value="">Select a category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category }}" @selected(old('category', $product->category) === $category)>{{ $category }}</option>
                                        @endforeach
                                    </select>
                                    <svg class="select-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </div>
                                @error('category')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Price (₱) *</label>
                                <input 
                                    type="number" 
                                    step="0.01" 
                                    min="0" 
                                    name="price" 
                                    class="form-control @error('price') is-invalid @enderror" 
                                    value="{{ old('price', $product->price) }}"
                                    placeholder="0.00"
                                    required
                                >
                                @error('price')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Stock *</label>
                                <input 
                                    type="number" 
                                    min="0" 
                                    name="stock" 
                                    class="form-control @error('stock') is-invalid @enderror" 
                                    value="{{ old('stock', $product->stock) }}"
                                    placeholder="0"
                                    required
                                >
                                @error('stock')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Description Section -->
                    <div class="form-section form-section-full">
                        <label class="form-label-main">Description</label>
                        <textarea 
                            name="description" 
                            class="form-control form-textarea @error('description') is-invalid @enderror"
                            placeholder="Add product details, usage instructions, or benefits..."
                            rows="4"
                        >{{ old('description', $product->description) }}</textarea>
                        <div class="character-count">
                            <span id="charCount">{{ strlen(old('description', $product->description ?? '')) }}</span>/1000
                        </div>
                        @error('description')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="form-section form-section-full">
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-2"></i>Save Changes
                            </button>
                            <a href="{{ route('retail.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-lg me-2"></i>Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>
        .retail-form-container {
            padding: 2rem;
            max-width: 900px;
            margin: 0 auto;
        }

        .retail-form-card {
            background: var(--pt-surface);
            border: 1px solid var(--pt-border);
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .retail-form-header {
            margin-bottom: 2.5rem;
            border-bottom: 2px solid var(--pt-border);
            padding-bottom: 1.5rem;
        }

        .retail-form-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--pt-text);
            margin: 0 0 0.5rem 0;
        }

        .retail-form-subtitle {
            font-size: 0.95rem;
            color: var(--pt-muted);
            margin: 0;
        }

        .form-grid {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .form-section {
            display: flex;
            flex-direction: column;
        }

        .form-section-full {
            width: 100%;
        }

        .form-label-main {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--pt-text);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 1rem;
            display: block;
        }

        /* Image Upload Styles */
        .image-upload-wrapper {
            position: relative;
        }

        .image-input {
            display: none;
        }

        .image-upload-area {
            border: 2px dashed var(--pt-border);
            border-radius: 10px;
            padding: 2.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: var(--pt-surface-soft);
        }

        .image-upload-area:hover {
            border-color: var(--pt-primary);
            background: color-mix(in srgb, var(--pt-primary) 5%, var(--pt-surface-soft));
        }

        .upload-icon {
            font-size: 3rem;
            color: var(--pt-primary);
            margin-bottom: 1rem;
        }

        .upload-main-text {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--pt-text);
            margin: 0;
        }

        .upload-sub-text {
            font-size: 0.85rem;
            color: var(--pt-muted);
            margin: 0.25rem 0 0 0;
        }

        .image-preview-wrapper {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 1rem;
            max-width: 300px;
        }

        .image-preview {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 8px;
        }

        .btn-remove-image {
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(0, 0, 0, 0.6);
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            transition: background 0.2s ease;
        }

        .btn-remove-image:hover {
            background: rgba(0, 0, 0, 0.8);
        }

        /* Form Grid Layout */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--pt-text);
            margin-bottom: 0.5rem;
        }

        .form-label::after {
            content: attr(data-required);
        }

        /* Input & Select Styles */
        .form-control,
        .form-select {
            background: var(--pt-surface-soft);
            border: 1px solid var(--pt-border);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            color: var(--pt-text);
            font-family: inherit;
            transition: all 0.2s ease;
        }

        /* Remove number input spinners */
        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }

        .form-control:focus,
        .form-select:focus {
            outline: none;
            border-color: var(--pt-primary);
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--pt-primary) 10%, transparent);
            background: var(--pt-surface);
        }

        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: #dc3545;
            background-color: color-mix(in srgb, #dc3545 5%, var(--pt-surface-soft));
        }

        .form-control::placeholder {
            color: var(--pt-muted);
        }

        .form-select {
            background: var(--pt-surface-soft);
            border: 1px solid var(--pt-border);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            color: var(--pt-text);
            font-family: inherit;
            transition: all 0.2s ease;
            width: 100%;
            cursor: pointer;
        }

        .form-select:focus {
            outline: none;
            border-color: var(--pt-primary);
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--pt-primary) 10%, transparent);
        }

        :root[data-theme="dark"] .form-select {
            background: var(--pt-surface-soft);
            color: var(--pt-text);
            border-color: var(--pt-border);
        }

        /* Custom Select Wrapper */
        .custom-select-wrapper {
            position: relative;
        }

        .custom-select {
            appearance: none;
        }

        .select-arrow {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            color: var(--pt-text);
            pointer-events: none;
        }

        :root[data-theme="dark"] .select-arrow {
            color: var(--pt-text);
        }

        /* Textarea Styles */
        .form-textarea {
            resize: vertical;
            min-height: 120px;
        }

        .character-count {
            font-size: 0.8rem;
            color: var(--pt-muted);
            margin-top: 0.5rem;
            text-align: right;
        }

        /* Error Message */
        .error-message {
            font-size: 0.85rem;
            color: #dc3545;
            margin-top: 0.25rem;
            display: block;
        }

        .alert {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 1rem;
            border-radius: 8px;
        }

        :root[data-theme="dark"] .alert {
            background-color: color-mix(in srgb, #dc3545 15%, var(--pt-surface));
            border-color: color-mix(in srgb, #dc3545 30%, var(--pt-border));
            color: #ff6b7a;
        }

        /* Action Buttons */
        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 0.5rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            font-size: 0.95rem;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .btn-primary {
            background: #0f1f33;
            color: #ffffff;
            border: 1px solid #0f1f33;
        }

        .btn-primary:hover {
            background: color-mix(in srgb, var(--pt-primary) 85%, black);
            border-color: color-mix(in srgb, var(--pt-primary) 85%, black);
            box-shadow: 0 4px 12px color-mix(in srgb, var(--pt-primary) 30%, transparent);
        }

        :root[data-theme="dark"] .btn-primary {
            background-color: #f5f8ff;
            border-color: #f5f8ff;
            color: #132033;
        }

        :root[data-theme="dark"] .btn-primary:hover {
            background-color: #d9e4f7;
            border-color: #d9e4f7;
            color: #132033;
            box-shadow: 0 4px 12px rgba(74, 111, 165, 0.2);
        }

        .btn-secondary {
            background: var(--pt-surface-soft);
            color: var(--pt-text);
            border: 1px solid var(--pt-border);
        }

        .btn-secondary:hover {
            background: var(--pt-border);
            border-color: var(--pt-border);
        }

        /* Responsive Design */
        /* Extra Small Screens: 320px - 480px (Mobile phones) */
        @media (max-width: 480px) {
            .retail-form-container {
                padding: 0.75rem;
                max-width: 100%;
                margin: 0;
            }

            .retail-form-card {
                padding: 1rem;
                border-radius: 8px;
            }

            .retail-form-header {
                margin-bottom: 1.5rem;
                padding-bottom: 1rem;
            }

            .retail-form-title {
                font-size: 1.25rem;
                margin-bottom: 0.25rem;
            }

            .retail-form-subtitle {
                font-size: 0.8rem;
            }

            .form-grid {
                gap: 1.25rem;
            }

            .form-label-main {
                font-size: 0.8rem;
                margin-bottom: 0.75rem;
            }

            .form-label {
                font-size: 0.8rem;
                margin-bottom: 0.4rem;
            }

            .form-control,
            .form-select {
                padding: 0.6rem 0.75rem;
                font-size: 0.85rem;
                border-radius: 6px;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 1rem;
                margin-bottom: 1rem;
            }

            .form-actions {
                flex-direction: column;
                gap: 0.75rem;
            }

            .btn {
                width: 100%;
                padding: 0.65rem 1rem;
                font-size: 0.85rem;
                justify-content: center;
            }

            .image-upload-area {
                padding: 1.25rem;
                border-radius: 8px;
            }

            .upload-icon {
                font-size: 2rem;
                margin-bottom: 0.75rem;
            }

            .upload-main-text {
                font-size: 0.85rem;
            }

            .upload-sub-text {
                font-size: 0.75rem;
            }

            .image-preview-wrapper {
                max-width: 100%;
            }

            .form-textarea {
                min-height: 100px;
                font-size: 0.85rem;
                padding: 0.6rem 0.75rem;
            }

            .character-count {
                font-size: 0.75rem;
            }

            .alert {
                padding: 0.75rem;
                font-size: 0.85rem;
            }

            .alert ul {
                margin: 0;
                padding-left: 1.25rem;
            }

            .alert li {
                font-size: 0.8rem;
                margin-bottom: 0.25rem;
            }
        }

        /* Small Screens: 481px - 768px (Tablets, small phones landscape) */
        @media (min-width: 481px) and (max-width: 768px) {
            .retail-form-container {
                padding: 1rem;
                max-width: 100%;
                margin: 0 auto;
            }

            .retail-form-card {
                padding: 1.5rem;
            }

            .retail-form-header {
                margin-bottom: 2rem;
                padding-bottom: 1.25rem;
            }

            .retail-form-title {
                font-size: 1.5rem;
            }

            .retail-form-subtitle {
                font-size: 0.9rem;
            }

            .form-grid {
                gap: 1.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 1.25rem;
                margin-bottom: 1.25rem;
            }

            .form-actions {
                flex-direction: column;
                gap: 0.75rem;
            }

            .btn {
                width: 100%;
                padding: 0.7rem 1.25rem;
                font-size: 0.9rem;
            }

            .image-upload-area {
                padding: 1.75rem;
            }

            .upload-icon {
                font-size: 2.5rem;
            }

            .image-preview-wrapper {
                max-width: 90%;
            }
        }

        /* Medium Screens: 769px - 1024px (Large tablets, small desktops) */
        @media (min-width: 769px) and (max-width: 1024px) {
            .retail-form-container {
                padding: 1.5rem;
                max-width: 100%;
            }

            .retail-form-card {
                padding: 2rem;
            }

            .form-row {
                grid-template-columns: 1fr 1fr;
                gap: 1.25rem;
                margin-bottom: 1.25rem;
            }

            .form-actions {
                flex-direction: row;
                gap: 1rem;
            }

            .btn {
                flex: 1;
                padding: 0.7rem 1.25rem;
            }

            .image-upload-area {
                padding: 2rem;
            }

            .image-preview-wrapper {
                max-width: 70%;
            }
        }

        /* Large Screens: 1025px+ (Desktops) */
        @media (min-width: 1025px) {
            .retail-form-container {
                padding: 2rem;
                max-width: 900px;
                margin: 0 auto;
            }

            .retail-form-card {
                padding: 2.5rem;
            }

            .form-row {
                grid-template-columns: 1fr 1fr;
            }

            .form-actions {
                flex-direction: row;
                gap: 1rem;
            }

            .btn {
                padding: 0.75rem 1.5rem;
            }

            .image-preview-wrapper {
                max-width: 300px;
            }
        }

        /* Extra Large Screens: 1441px+ */
        @media (min-width: 1441px) {
            .retail-form-container {
                max-width: 1000px;
                padding: 2.5rem;
            }

            .retail-form-card {
                padding: 3rem;
            }

            .form-grid {
                gap: 2.5rem;
            }

            .retail-form-title {
                font-size: 2rem;
            }
        }
    </style>

    <script>
        const imageInput = document.getElementById('imageInput');
        const imageUploadArea = document.getElementById('imageUploadArea');
        const imagePreviewWrapper = document.getElementById('imagePreviewWrapper');
        const imagePreview = document.getElementById('imagePreview');
        const charCountSpan = document.getElementById('charCount');
        const descriptionField = document.querySelector('textarea[name="description"]');

        // Handle file selection
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    imagePreview.src = event.target.result;
                    imagePreviewWrapper.style.display = 'block';
                    imageUploadArea.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });

        // Handle drag and drop
        imageUploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            imageUploadArea.style.borderColor = 'var(--pt-primary)';
        });

        imageUploadArea.addEventListener('dragleave', () => {
            imageUploadArea.style.borderColor = 'var(--pt-border)';
        });

        imageUploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            imageUploadArea.style.borderColor = 'var(--pt-border)';
            const file = e.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                imageInput.files = e.dataTransfer.files;
                const reader = new FileReader();
                reader.onload = function(event) {
                    imagePreview.src = event.target.result;
                    imagePreviewWrapper.style.display = 'block';
                    imageUploadArea.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        });

        // Character count for description
        descriptionField.addEventListener('input', function() {
            charCountSpan.textContent = this.value.length;
        });

        function removeImage(e) {
            e.preventDefault();
            imageInput.value = '';
            imagePreviewWrapper.style.display = 'none';
            imageUploadArea.style.display = 'block';
        }
    </script>
@endsection

