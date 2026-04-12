@extends('layouts.admin')

@php
    $hideTopbarTitle = true;
@endphp

@section('content')
    <section class="bootstrap-dashboard">
        <div class="dashboard-stage">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-8">
                    <div class="card dashboard-bootstrap-card">
                        <div class="card-body p-5">
                            <h1 class="page-title mb-5">Add New Product</h1>

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

                            <form method="POST" action="{{ route('retail.products.store') }}" enctype="multipart/form-data" class="product-form">
                @csrf

                <div class="form-grid">
                    <!-- Image Upload Section -->
                    <div class="form-section form-section-full">
                        <label class="form-label-main">Product Photo</label>
                        <div class="image-upload-wrapper">
                            <input type="file" id="imageInput" name="image" class="image-input" accept="image/*">
                            <div class="image-upload-area" onclick="document.getElementById('imageInput').click()">
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
                                    value="{{ old('name') }}" 
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
                                            <option value="{{ $category }}" @selected(old('category') === $category)>{{ $category }}</option>
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
                                    value="{{ old('price') }}"
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
                                    value="{{ old('stock', 0) }}"
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
                        >{{ old('description') }}</textarea>
                        <div class="character-count">
                            <span id="charCount">0</span>/1000
                        </div>
                        @error('description')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="form-section form-section-full">
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-2"></i>Add Product
                            </button>
                            <a href="{{ route('retail.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-lg me-2"></i>Cancel
                            </a>
                        </div>
                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .section-heading {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--pt-text);
        }

        .product-form .form-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--pt-muted);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
        }

        .product-form .form-control,
        .product-form .form-select {
            background: var(--pt-surface-soft);
            border: 1px solid var(--pt-border);
            color: var(--pt-text);
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.2s ease;
        }

        .product-form .form-select {
            padding-right: 2.5rem;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23666666' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
        }

        .product-form .form-control::placeholder {
            color: var(--pt-muted);
        }

        .product-form .form-control:focus,
        .product-form .form-select:focus {
            background: var(--pt-surface);
            border-color: #5b8def;
            color: var(--pt-text);
            box-shadow: 0 0 0 3px rgba(91, 141, 239, 0.1);
        }

        .product-form .input-group-text {
            background: var(--pt-surface-soft);
            border: 1px solid var(--pt-border);
            color: var(--pt-muted);
            font-weight: 600;
        }

        .btn-primary {
            background: #1a1a1a;
            border: 1.5px solid transparent;
            color: #ffffff;
            padding: 0.875rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-primary:hover {
            background: #2d2d2d;
            color: #ffffff;
            border-color: #ffffff;
        }

        :root[data-theme="dark"] .btn-primary {
            background: #ffffff;
            border: 1.5px solid transparent;
            color: #1a1a1a;
            box-shadow: 0 2px 8px rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        :root[data-theme="dark"] .btn-primary:hover {
            background: #f5f5f5;
            color: #1a1a1a;
            border-color: #1a1a1a;
        }

        .btn-secondary {
            background: transparent;
            border: 1.5px solid var(--pt-text);
            color: var(--pt-text);
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: var(--pt-surface-soft);
            color: var(--pt-text);
            border-color: var(--pt-text);
        }

        :root[data-theme="dark"] .btn-secondary {
            background: transparent;
            border: 1.5px solid rgba(255, 255, 255, 0.6);
            color: var(--pt-text);
            transition: all 0.3s ease;
        }

        :root[data-theme="dark"] .btn-secondary:hover {
            background: var(--pt-surface-soft);
            border-color: #ffffff;
            color: var(--pt-text);
        }

        .text-danger {
            color: var(--pt-accent);
        }

        :root[data-theme="dark"] .form-control,
        :root[data-theme="dark"] .form-select {
            background-color: var(--pt-surface-soft) !important;
            border-color: var(--pt-border) !important;
            color: var(--pt-text) !important;
        }

        :root[data-theme="dark"] .form-select {
            background-color: var(--pt-surface-soft) !important;
            border-color: var(--pt-border) !important;
            color: var(--pt-text) !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%239cb0c9' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
            background-repeat: no-repeat !important;
            background-position: right 0.75rem center !important;
            background-size: 16px 12px !important;
            padding-right: 2.5rem !important;
        }

        :root[data-theme="dark"] .form-control::placeholder {
            color: var(--pt-muted);
        }

        :root[data-theme="dark"] .form-control:focus,
        :root[data-theme="dark"] .form-select:focus {
            background-color: var(--pt-surface) !important;
            border-color: #5b8def !important;
            color: var(--pt-text) !important;
        }

        :root[data-theme="dark"] .input-group-text {
            background-color: transparent !important;
            border-color: transparent !important;
            color: var(--pt-text);
        }

        :root[data-theme="dark"] .product-form .input-group {
            border-color: var(--pt-border) !important;
        }

        :root[data-theme="dark"] .product-form .input-group:focus-within {
            border-color: #5b8def !important;
            box-shadow: 0 0 0 0.2rem rgba(91, 141, 239, 0.15) !important;
        }

        /* Remove number spinner arrows */
        .product-form .no-spinner::-webkit-outer-spin-button,
        .product-form .no-spinner::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
            display: none;
        }

        .product-form .no-spinner[type=number] {
            -moz-appearance: textfield;
        }

        /* Input group styling */
        .product-form .input-group {
            border: 1px solid var(--pt-border);
            border-radius: 0.375rem;
            overflow: hidden;
            transition: all 0.2s ease;
            display: flex;
            align-items: stretch;
        }

        .product-form .input-group:focus-within {
            border-color: #5b8def;
            box-shadow: 0 0 0 0.2rem rgba(91, 141, 239, 0.15);
        }

        .product-form .input-group-text {
            background-color: transparent;
            border: none;
            font-weight: 600;
            padding: 0.75rem 0.75rem;
            color: var(--pt-text);
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 50px;
        }

        .product-form .input-group .form-control {
            border: none;
            padding-left: 0.5rem;
        }

        .product-form .input-group .form-control:focus {
            border: none;
            box-shadow: none;
            outline: none;
        }

        :root[data-theme="dark"] .product-form .input-group-text {
            color: var(--pt-text);
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 1.5rem !important;
            }

            .section-heading {
                font-size: 1.25rem;
            }
        }

        /* Product-specific styles */
        .image-upload-area {
            border: 2px dashed var(--pt-border);
            border-radius: 10px;
            padding: 2rem;
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
            font-size: 2.5rem;
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

        .form-section {
            display: flex;
            flex-direction: column;
            margin-bottom: 1.5rem;
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

        .alert ul {
            margin: 0;
            padding-left: 1.5rem;
        }

        .alert li {
            margin-bottom: 0.5rem;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
            padding-top: 1.5rem;
        }

        .btn {
            padding: 0.875rem 2rem;
            font-weight: 700;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 0.95rem;
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

        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }

        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: #dc3545;
            background-color: color-mix(in srgb, #dc3545 5%, var(--pt-surface-soft));
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--pt-text);
            margin-bottom: 1.5rem;
        }

        .image-input {
            display: none;
        }

        .image-upload-wrapper {
            position: relative;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .card-body {
                padding: 1.5rem !important;
            }
        }

        @media (max-width: 480px) {
            .card-body {
                padding: 1rem !important;
            }

            .page-title {
                font-size: 1.5rem;
                margin-bottom: 1.5rem;
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
    </style>

    <script>
        const imageInput = document.getElementById('imageInput');
        const imageUploadArea = document.querySelector('.image-upload-area');
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

