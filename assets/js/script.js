function validateForm() {
    let email = document.getElementById("email");
    let password = document.getElementById("password");
    if (!email || !password) return true;
    if (email.value === "" || password.value === "") {
        alert("Email dan Password tidak boleh kosong!");
        return false;
    }
    return true;
}

function konfirmasiHapus(url, pesan) {
    if (confirm(pesan || 'Yakin ingin menghapus data ini?')) {
        window.location.href = url;
    }
}
