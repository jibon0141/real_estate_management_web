@extends("admin.master")
@section("content")
<div class="min-h-screen p-4 sm:p-6 lg:p-8" style="background: linear-gradient(135deg, #f0f4ff 0%, #e8ecf8 100%);">
    <div class="max-w-4xl mx-auto">
        @include('admin.include.message')
        <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                        <i class="fa fa-plus-circle text-sm"></i>
                    </span>
                    Create New Project
                </h3>
                <p class="text-sm text-gray-500 mt-1 ml-13">Add a new project to the system</p>
            </div>
            <a href="{{ route('admin.project.index') }}"
               class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium px-4 py-2.5 rounded-xl border border-gray-200 shadow-sm transition-all duration-200 hover:shadow-md">
                <i class="fa fa-reply"></i>
                Back to Project List
            </a>
        </div>
        <div class="bg-white/80 rounded-2xl shadow-lg shadow-indigo-100/50 border border-white/50 overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex items-center gap-2">
                <i class="fa fa-info-circle text-emerald-500"></i>
                <span class="font-semibold text-gray-700">Project Information</span>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.project.create') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="flex flex-col">
                            <label class="mb-2 text-sm font-medium text-gray-600">
                                Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                   class="form-input !rounded-xl !border-gray-200 !bg-gray-50 focus:!bg-white focus:!border-indigo-400 focus:!ring focus:!ring-indigo-200/50 !transition-all"
                                   placeholder="Enter project name">
                            @error('name')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex flex-col">
                            <label class="mb-2 text-sm font-medium text-gray-600">
                                Image
                            </label>
                            <div class="relative">
                                <input type="file" name="image" id="imageInput"
                                       class="form-input !rounded-xl !border-gray-200 !bg-gray-50 focus:!bg-white focus:!border-indigo-400 focus:!ring focus:!ring-indigo-200/50 !transition-all !pl-10 file:!rounded-lg file:!border-0 file:!bg-indigo-50 file:!text-indigo-700 file:!text-sm file:!font-medium file:!px-3 file:!py-1.5 hover:file:!bg-indigo-100"
                                       accept="image/*">
                            </div>
                            @error('image')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                            <div id="imagePreview" class="mt-2 hidden">
                                <img src="" alt="Preview" class="w-32 h-32 object-cover rounded-xl border border-gray-200">
                            </div>
                        </div>
                        <div class="flex flex-col md:col-span-2">
                            <label class="mb-2 text-sm font-medium text-gray-600">
                                Description
                            </label>
                            <textarea name="description" rows="4"
                                      class="form-input !rounded-xl !border-gray-200 !bg-gray-50 focus:!bg-white focus:!border-indigo-400 focus:!ring focus:!ring-indigo-200/50 !transition-all"
                                      placeholder="Enter project description">{{ old('description') }}</textarea>
                            @error('description')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="flex flex-col">
                            <label class="mb-2 text-sm font-medium text-gray-600">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <div class="flex gap-4 h-11 items-center">
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="status" value="1" checked
                                           class="text-indigo-600 focus:ring-indigo-500 rounded-full">
                                    <span class="text-sm text-gray-600">Active</span>
                                </label>
                                <label class="inline-flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="status" value="0"
                                           class="text-red-500 focus:ring-red-400 rounded-full">
                                    <span class="text-sm text-gray-600">Inactive</span>
                                </label>
                            </div>
                            @error('status')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-8 pt-5 border-t border-gray-100 flex justify-end gap-3">
                        <a href="{{ route('admin.project.index') }}"
                           class="px-6 py-2.5 text-sm font-medium text-gray-600 bg-gray-50 hover:bg-gray-100 rounded-xl border border-gray-200 transition-all w-full sm:w-auto text-center">
                            Cancel
                        </a>
                        <button type="submit"
                                class="px-6 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 rounded-xl shadow-lg shadow-emerald-200 transition-all duration-200 hover:shadow-xl hover:-translate-y-0.5 w-full sm:w-auto">
                            <i class="fa fa-save mr-1.5"></i>
                            Save Project
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    document.getElementById('imageInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const preview = document.getElementById('imagePreview');
                preview.querySelector('img').src = event.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection
