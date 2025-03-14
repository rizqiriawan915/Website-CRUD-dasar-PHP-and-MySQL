document.addEventListener("DOMContentLoaded", function () {
    let currentPage = 1;
    let searchQuery = "";

    function loadData(page, search = "") {
        let xhr = new XMLHttpRequest();
        xhr.open("GET", "get_karyawan.php?page=" + page + "&search=" + search, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                document.getElementById("karyawan-body").innerHTML = xhr.responseText;
                document.getElementById("page-number").textContent = page;
            }
        };
        xhr.send();
    }

    document.getElementById("next-btn").addEventListener("click", function () {
        currentPage++;
        loadData(currentPage, searchQuery);
    });

    document.getElementById("prev-btn").addEventListener("click", function () {
        if (currentPage > 1) {
            currentPage--;
            loadData(currentPage, searchQuery);
        }
    });

    document.getElementById("search").addEventListener("keyup", function () {
        searchQuery = this.value;
        loadData(1, searchQuery); // Cari dari halaman pertama
    });

    loadData(currentPage); // Load halaman pertama saat awal
});
