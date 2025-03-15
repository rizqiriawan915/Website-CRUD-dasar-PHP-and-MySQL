// Form validation on client-side
document.querySelector('form').addEventListener('submit', function(e) {
    const nama = document.getElementById('nama_karyawan').value.trim();
    const email = document.getElementById('email').value.trim();
    const telepon = document.getElementById('no_telepon').value.trim();
    const jabatan = document.getElementById('jabatan').value.trim();
    
    if (nama === '') {
        alert('Nama karyawan tidak boleh kosong');
        e.preventDefault();
        return false;
    }
    
    if (email === '') {
        alert('Email tidak boleh kosong');
        e.preventDefault();
        return false;
    }
    
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        alert('Format email tidak valid');
        e.preventDefault();
        return false;
    }
    
    if (telepon === '') {
        alert('Nomor telepon tidak boleh kosong');
        e.preventDefault();
        return false;
    }
    
    if (isNaN(telepon)) {
        alert('Nomor telepon harus berupa angka');
        e.preventDefault();
        return false;
    }
    
    if (jabatan === '') {
        alert('Jabatan tidak boleh kosong');
        e.preventDefault();
        return false;
    }
});