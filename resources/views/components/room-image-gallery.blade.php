<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
    @forelse($images as $img)
        <div class="relative group overflow-hidden rounded-xl shadow-md border border-gray-200 bg-white">
            <img
                src="{{ asset('storage/' . $img) }}"
                class="w-full h-32 object-cover transition-transform duration-300 group-hover:scale-105"
                alt="Room Image"
            >
            <div class="absolute inset-0 bg-black bg-opacity-10 group-hover:bg-opacity-20 transition"></div>
        </div>
    @empty
        <div class="col-span-full text-center text-gray-400 italic">
            No images available.
        </div>
    @endforelse
</div>
