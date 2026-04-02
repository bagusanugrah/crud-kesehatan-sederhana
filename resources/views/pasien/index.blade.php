<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Data Pasien Laravel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen text-slate-800 p-4 md:p-8">

    <div class="max-w-6xl mx-auto">
        <header class="mb-8 text-center md:text-left">
            <h1 class="text-3xl font-bold text-teal-600">Sistem Data Pasien</h1>
            <p class="text-slate-500 mt-2">Manajemen CRUD Kesehatan (Laravel + MySQL)</p>
        </header>

        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
            {{ session('success') }}
        </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8">
            
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 w-full lg:w-1/3 h-fit">
                <h2 id="formTitle" class="text-xl font-semibold mb-4 text-slate-700">Tambah Pasien</h2>
                
                <form id="pasienForm" action="{{ route('pasien.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div id="methodField"></div> <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Nama Lengkap</label>
                        <input type="text" id="inputNama" name="nama" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Umur</label>
                        <input type="number" id="inputUmur" name="umur" required class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-600 mb-1">Diagnosa</label>
                        <textarea id="inputDiagnosa" name="diagnosa" required rows="3" class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-500 outline-none resize-none"></textarea>
                    </div>
                    
                    <button type="submit" id="btnSubmit" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-medium py-2.5 rounded-lg transition-colors">
                        Simpan Data
                    </button>
                    <button type="button" id="btnCancel" onclick="batalEdit()" class="hidden w-full bg-slate-100 hover:bg-slate-200 text-slate-600 font-medium py-2.5 rounded-lg transition-colors mt-2">
                        Batal Edit
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 w-full lg:w-2/3 overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h2 class="text-xl font-semibold text-slate-700">Daftar Pasien</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 text-sm uppercase tracking-wider">
                                <th class="px-6 py-4 font-medium">Nama</th>
                                <th class="px-6 py-4 font-medium">Umur</th>
                                <th class="px-6 py-4 font-medium">Diagnosa</th>
                                <th class="px-6 py-4 font-medium text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($pasiens as $pasien)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 font-medium text-slate-800">{{ $pasien->nama }}</td>
                                <td class="px-6 py-4">{{ $pasien->umur }} Tahun</td>
                                <td class="px-6 py-4"><span class="bg-teal-50 text-teal-700 px-3 py-1 rounded-full text-sm">{{ $pasien->diagnosa }}</span></td>
                                <td class="px-6 py-4 text-center flex justify-center space-x-2">
                                    
                                    <button onclick="editData({{ $pasien->id }}, '{{ $pasien->nama }}', {{ $pasien->umur }}, '{{ $pasien->diagnosa }}')" class="text-blue-600 hover:text-blue-800 font-medium text-sm px-2 py-1 rounded hover:bg-blue-50">
                                        Edit
                                    </button>

                                    <form action="{{ route('pasien.destroy', $pasien->id) }}" method="POST" onsubmit="return confirm('Hapus data pasien ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-sm px-2 py-1 rounded hover:bg-red-50">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-500">Belum ada data pasien tersimpan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script>
        const form = document.getElementById('pasienForm');
        const methodField = document.getElementById('methodField');
        const formTitle = document.getElementById('formTitle');
        const btnSubmit = document.getElementById('btnSubmit');
        const btnCancel = document.getElementById('btnCancel');
        
        const inputNama = document.getElementById('inputNama');
        const inputUmur = document.getElementById('inputUmur');
        const inputDiagnosa = document.getElementById('inputDiagnosa');

        function editData(id, nama, umur, diagnosa) {
            // Ubah action form mengarah ke route update
            form.action = `/pasien/${id}`;
            
            // Tambahkan method PUT untuk Laravel
            methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
            
            // Isi form dengan data yang mau diedit
            inputNama.value = nama;
            inputUmur.value = umur;
            inputDiagnosa.value = diagnosa;
            
            // Ubah UI
            formTitle.innerText = 'Edit Pasien';
            btnSubmit.innerText = 'Update Data';
            btnSubmit.classList.replace('bg-teal-600', 'bg-blue-600');
            btnSubmit.classList.replace('hover:bg-teal-700', 'hover:bg-blue-700');
            btnCancel.classList.remove('hidden');

            // Scroll ke atas otomatis
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function batalEdit() {
            // Kembalikan ke mode Tambah
            form.reset();
            form.action = "{{ route('pasien.store') }}";
            methodField.innerHTML = '';
            
            formTitle.innerText = 'Tambah Pasien';
            btnSubmit.innerText = 'Simpan Data';
            btnSubmit.classList.replace('bg-blue-600', 'bg-teal-600');
            btnSubmit.classList.replace('hover:bg-blue-700', 'hover:bg-teal-700');
            btnCancel.classList.add('hidden');
        }
    </script>
</body>
</html>