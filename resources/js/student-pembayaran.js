// resources/js/student-pembayaran.js
$(document).ready(function () {
    // Auto-refresh status pembayaran setiap 30 detik
    function refreshStatusPembayaran() {
        $.ajax({
            url: "/student/pembayaran/status-update",
            type: "GET",
            dataType: "json",
            success: function (data) {
                $.each(data, function (index, pembayaran) {
                    let badge = $("#status-pembayaran-" + pembayaran.id);
                    if (badge.length) {
                        let statusClass = "";
                        let statusText = "";

                        switch (pembayaran.status) {
                            case "lunas":
                                statusClass = "success";
                                statusText = "LUNAS";
                                break;
                            case "menunggu_verifikasi":
                                statusClass = "warning";
                                statusText = "MENUNGGU";
                                break;
                            default:
                                statusClass = "danger";
                                statusText = "BELUM LUNAS";
                        }

                        badge
                            .removeClass("bg-success bg-warning bg-danger")
                            .addClass("bg-" + statusClass)
                            .text(statusText);
                    }
                });
            },
        });
    }

    // Jalankan setiap 30 detik
    setInterval(refreshStatusPembayaran, 30000);
});
