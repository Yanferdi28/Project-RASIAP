<div class="p-6">
    <h3 class="text-lg font-medium mb-4">Pilih Format dan Rentang Tanggal</h3>

    <form id="daftar-isi-berkas-form">
        <div class="space-y-4">
            <div>
                <label for="format_ekspor" class="block text-sm font-medium text-gray-700 mb-1">Format Ekspor</label>
                <select
                    id="format_ekspor"
                    name="format_ekspor"
                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    required
                >
                    <option value="">Pilih Format</option>
                    <option value="pdf">PDF</option>
                    <option value="excel">Excel</option>
                </select>
            </div>

            <div>
                <label for="tanggal_dari" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                <input
                    type="date"
                    id="tanggal_dari"
                    name="tanggal_dari"
                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    required
                />
                <p class="text-xs text-gray-500 mt-1">Tanggal awal wajib diisi</p>
            </div>

            <div>
                <label for="tanggal_sampai" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                <input
                    type="date"
                    id="tanggal_sampai"
                    name="tanggal_sampai"
                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    required
                />
                <p class="text-xs text-gray-500 mt-1">Tanggal akhir wajib diisi</p>
            </div>
        </div>

        <div class="mt-6 flex space-x-3">
            <button
                type="button"
                id="pdf-export-btn"
                class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                onclick="submitDaftarIsiForm('pdf')"
            >
                Ekspor PDF
            </button>
            <button
                type="button"
                id="excel-export-btn"
                class="px-4 py-2 bg-success-600 text-white rounded-lg hover:bg-success-700 focus:outline-none focus:ring-2 focus:ring-success-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                onclick="submitDaftarIsiForm('excel')"
            >
                Ekspor Excel
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tanggalDari = document.getElementById('tanggal_dari');
    const tanggalSampai = document.getElementById('tanggal_sampai');
    const pdfBtn = document.getElementById('pdf-export-btn');
    const excelBtn = document.getElementById('excel-export-btn');

    function validateDates() {
        const dariValue = tanggalDari.value;
        const sampaiValue = tanggalSampai.value;

        if (dariValue && sampaiValue) {
            pdfBtn.disabled = false;
            excelBtn.disabled = false;
            return true;
        } else {
            pdfBtn.disabled = true;
            excelBtn.disabled = true;
            return false;
        }
    }

    tanggalDari.addEventListener('change', validateDates);
    tanggalSampai.addEventListener('change', validateDates);
});

function submitDaftarIsiForm(format) {
    const tanggalDari = document.getElementById('tanggal_dari').value;
    const tanggalSampai = document.getElementById('tanggal_sampai').value;

    // Validate required fields
    if (!tanggalDari || !tanggalSampai) {
        alert('Silakan isi rentang tanggal terlebih dahulu.');
        return;
    }

    // Check if dates are valid
    if (new Date(tanggalDari) > new Date(tanggalSampai)) {
        alert('Tanggal "Dari" tidak boleh lebih besar dari tanggal "Sampai".');
        return;
    }

    // Show confirmation dialog before proceeding
    if (confirm('Anda yakin ingin mencetak dengan rentang tanggal yang dipilih?')) {
        // Submit the form to the appropriate endpoint
        const url = format === 'pdf' ?
            `{{ route('daftar-isi-berkas.pdf', ['tanggal_dari' => '__dari__', 'tanggal_sampai' => '__sampai__']) }}`
            : `{{ route('daftar-isi-berkas.excel', ['tanggal_dari' => '__dari__', 'tanggal_sampai' => '__sampai__']) }}`;

        const finalUrl = url
            .replace('__dari__', tanggalDari)
            .replace('__sampai__', tanggalSampai);

        window.location.href = finalUrl;
    }
}
</script>