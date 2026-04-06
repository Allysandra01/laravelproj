<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Posts') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">


                    <!-- Create Button -->
                    <button onclick="openCreateModal()"
                        class="bg-indigo-600 text-white px-4 py-2 rounded">
                        Create Post
                    </button>

                    <h1 class="text-2xl font-bold mt-4 mb-6">Posts List</h1>

                    <!-- Table -->
                    <div class="mt-6 bg-white shadow rounded">
                        <table class="w-full text-left">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="p-3">Title</th>
                                    <th class="p-3">Content</th>
                                    <th class="p-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($posts as $post)
                                <tr class="border-b">
                                    <td class="p-3">{{ $post->title }}</td>
                                    <td class="p-3">{{ $post->content }}</td>
                                    <td class="p-3 space-x-2">
                                        <button
                                            onclick="openEditModal({{ $post->id }}, '{{ $post->title }}', '{{ $post->content }}')"
                                            class="bg-yellow-500 text-white px-2 py-1 rounded">
                                            Edit
                                        </button>


                                        <form id="delete-form-{{ $post->id }}"
                                            action="{{ route('posts.destroy', $post->id) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                onclick="confirmDelete({{ $post->id }})"
                                                class="bg-red-600 text-white px-2 py-1 rounded">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div id="postModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white w-1/3 p-6 rounded shadow">
            <!-- Modal Header -->
            <div class="flex justify-between items-center mb-4">
                <h2 id="modalTitle" class="text-xl font-bold text-gray-800">
                    Create Post
                </h2>


                <button onclick="closeModal()"
                    class="text-gray-500 hover:text-gray-700 text-xl">
                    &times;
                </button>
            </div>
            <form id="postForm" method="POST">
                @csrf
                <input type="hidden" id="methodField" name="_method">


                <div class="mb-4">
                    <label>Title</label>
                    <input type="text" name="title" id="title"
                        class="w-full border rounded p-2">
                </div>


                <div class="mb-4">
                    <label>Content</label>
                    <textarea name="content" id="content"
                        class="w-full border rounded p-2"></textarea>
                </div>


                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal()"
                        class="bg-gray-500 text-white px-3 py-1 rounded">
                        Cancel
                    </button>
                    <button type="submit"
                        class="bg-indigo-600 text-white px-3 py-1 rounded">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: "{{ session('success') }}",
            timer: 2000,
            showConfirmButton: false
        });
    </script>
    @endif


    <script>
        function openCreateModal() {
            document.getElementById('modalTitle').innerText = "Create Post";
            document.getElementById('postForm').action = "{{ route('posts.store') }}";
            document.getElementById('methodField').value = '';
            document.getElementById('title').value = '';
            document.getElementById('content').value = '';
            document.getElementById('postModal').classList.remove('hidden');
        }


        function openEditModal(id, title, content) {
            document.getElementById('modalTitle').innerText = "Edit Post";
            document.getElementById('postForm').action = "/posts/" + id;
            document.getElementById('methodField').value = 'PUT';
            document.getElementById('title').value = title;
            document.getElementById('content').value = content;
            document.getElementById('postModal').classList.remove('hidden');
        }


        function closeModal() {
            document.getElementById('postModal').classList.add('hidden');
        }


        function confirmDelete(id) {
            Swal.fire({
                title: 'Delete Post',
                text: 'Are you sure to delete this post?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>


</x-app-layout>
