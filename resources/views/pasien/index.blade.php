<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Data Pasien - V2</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen text-gray-800 p-4 md:p-8 font-sans">

    <div class="max-w-6xl mx-auto">
        <header class="mb-8 text-center md:text-left bg-white p-6 rounded-2xl shadow-md border-l-4 border-indigo-500">
            <h1 class="text-4xl font-extrabold bg-gradient-to-r from-indigo-600 to-purple-600 text-transparent bg-clip-text">Sistem Data Pasien v2.0</h1>
            <p class="text-gray-500 mt-2 font-medium">Tema Baru Hasil Otomatisasi CI/CD</p>
        </header>

        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-md">
            {{ session('success') }}
        </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8">
            
            <div class="bg-white p-6 rounded-2xl shadow-md border-t-4 border-indigo-500 w-full lg:w-1/3 h-fit">
                <h2 id="formTitle" class="text-xl font-bold mb-4 text-indigo-700">Tambah Pasien Baru</h2>
                
                <form id="pasienForm" action="{{ route('pasien.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div id="methodField"></div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" id="inputNama" name="nama" required class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Umur</label>
                        <input type="number" id="inputUmur" name="umur" required class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Diagnosa</label>
                        <textarea id="inputDiagnosa" name="diagnosa" required rows="3" class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition-all resize-none"></textarea>
                    </div>
                    
                    <button type="submit" id="btnSubmit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-3 rounded-lg transition-all shadow-lg transform hover:-translate-y-0.5">
                        Simpan Data
                    </button>
                    <button type="button" id="btnCancel" onclick="batalEdit()" class="hidden w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-3 rounded-lg transition-all mt-3">
                        Batal Edit
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-2xl shadow-md border border-gray-200 w-full lg:w-2/3 overflow-hidden">
                <div class="p-6 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-800">Daftar Pasien</h2>
                    <span class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-3 py-1 rounded-full">Real-time Data</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-indigo-50 text-indigo-700 text-sm uppercase tracking-wider">
                                <th class="px-6 py-4 font-bold">Nama</th>
                                <th class="px-6 py-4 font-bold">Umur</th>
                                <th class="px-6 py-4 font-bold">Diagnosa</th>
                                <th class="px-6 py-4 font-bold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($pasiens as $pasien)
                            <tr class="hover:bg-indigo-50 transition-colors">
                                <td class="px-6 py-4 font-semibold text-gray-800">{{ $pasien->nama }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $pasien->umur }} Tahun</td>
                                <td class="px-6 py-4"><span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-sm font-medium">{{ $pasien->diagnosa }}</span></td>
                                <td class="px-6 py-4 text-center flex justify-center space-x-2">
                                    
                                    <button onclick="editData({{ $pasien->id }}, '{{ $pasien->nama }}', {{ $pasien->umur }}, '{{ $pasien->diagnosa }}')" class="text-indigo-600 hover:text-indigo-900 bg-indigo-100 hover:bg-indigo-200 font-semibold text-sm px-3 py-1.5 rounded transition-colors">
                                        Edit
                                    </button>

                                    <form action="{{ route('pasien.destroy', $pasien->id) }}" method="POST" onsubmit="return confirm('Yakin mau menghapus data pasien ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-900 bg-rose-100 hover:bg-rose-200 font-semibold text-sm px-3 py-1.5 rounded transition-colors">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500 font-medium">Belum ada data pasien tersimpan.</td>
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
            form.action = `/pasien/${id}`;
            methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
            
            inputNama.value = nama;
            inputUmur.value = umur;
            inputDiagnosa.value = diagnosa;
            
            formTitle.innerText = 'Edit Data Pasien';
            btnSubmit.innerText = 'Update Data';
            
            btnSubmit.classList.remove('from-indigo-600', 'to-purple-600', 'hover:from-indigo-700', 'hover:to-purple-700');
            btnSubmit.classList.add('from-amber-500', 'to-orange-500', 'hover:from-amber-600', 'hover:to-orange-600');
            
            btnCancel.classList.remove('hidden');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function batalEdit() {
            form.reset();
            form.action = "{{ route('pasien.store') }}";
            methodField.innerHTML = '';
            
            formTitle.innerText = 'Tambah Pasien Baru';
            btnSubmit.innerText = 'Simpan Data';
            
            btnSubmit.classList.remove('from-amber-500', 'to-orange-500', 'hover:from-amber-600', 'hover:to-orange-600');
            btnSubmit.classList.add('from-indigo-600', 'to-purple-600', 'hover:from-indigo-700', 'hover:to-purple-700');
            
            btnCancel.classList.add('hidden');
        }
    </script>
</body>
</html>