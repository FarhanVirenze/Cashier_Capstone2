import { BrowserMultiFormatReader } from "@zxing/browser";

window.addEventListener("DOMContentLoaded", async () => {
    const previewElem = document.getElementById("preview");
    const flashBtn = document.getElementById("flash-toggle");
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

    if (!previewElem || !csrf) return;

    const codeReader = new BrowserMultiFormatReader();
    let currentTrack = null;
    let torchOn = false;
    let hasScanned = false;

    /* ===============================
       🔔 TOAST NOTIFICATION
    =============================== */
    function showToast(message, type = "error") {
        const container = document.getElementById("toast-container");
        if (!container) return;

        const toast = document.createElement("div");
        toast.textContent = message;
        toast.className = `
            px-4 py-2 rounded shadow-lg text-white 
            ${type === "success" ? "bg-green-500" : "bg-red-500"}
            animate-slide-in
        `;
        container.appendChild(toast);

        setTimeout(() => toast.remove(), 3000);
    }

    /* ===============================
       🛒 LOAD CART (SAMA SEPERTI SEBELUMNYA)
    =============================== */
    async function loadCart() {
        try {
            const res = await fetch("/scan/cart");
            const data = await res.json();

            const list = document.getElementById("cart-items");
            const totalEl = document.getElementById("cart-total");
            const emptyEl = document.getElementById("cart-empty");
            const countEl = document.getElementById("cart-count");

            if (!list || !totalEl || !emptyEl || !countEl) return;

            list.querySelectorAll(".cart-item").forEach((el) => el.remove());

            if (data.items.length === 0) {
                emptyEl.style.display = "block";
                totalEl.textContent = "Rp 0";
                countEl.textContent = "0 item";
                return;
            }

            emptyEl.style.display = "none";

            data.items.forEach((item) => {
                const div = document.createElement("div");
                div.className =
                    "cart-item flex justify-between items-center text-white text-sm border-b border-white/10 py-2";

                div.innerHTML = `
                    <div>
                        <div class="font-semibold leading-tight">
                            ${item.product.nama}
                        </div>
                        <div class="text-xs opacity-70">
                            Rp ${item.product.harga.toLocaleString("id-ID")}
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <button onclick="updateQty(${item.product.id}, ${
                    item.quantity - 1
                })"
                            class="w-7 h-7 rounded bg-red-600 text-white font-bold">−</button>

                        <input type="number" value="${item.quantity}" min="1"
                            class="w-auto min-w-[2.5rem] px-1 h-7 text-center rounded bg-white text-black font-bold"
                            style="max-width: 4rem"
                            oninput="updateQty(${
                                item.product.id
                            }, Number(this.value))"
                        />

                        <button onclick="updateQty(${item.product.id}, ${
                    item.quantity + 1
                })"
                            class="w-7 h-7 rounded bg-blue-600 text-white font-bold">+</button>
                    </div>
                `;

                list.appendChild(div);
            });

            totalEl.textContent = `Rp ${data.total.toLocaleString("id-ID")}`;
            const totalItems = data.items.reduce(
                (sum, item) => sum + item.quantity,
                0
            );
            countEl.textContent = `${totalItems} item${
                totalItems > 1 ? "s" : ""
            }`;
        } catch (err) {
            console.error("Gagal load cart:", err);
            showToast("⚠️ Gagal memuat keranjang");
        }
    }

    /* ===============================
       ➕ / ➖ / INPUT UPDATE QUANTITY
    =============================== */
    window.updateQty = async (productId, qty) => {
        qty = parseInt(qty, 10);
        if (isNaN(qty) || qty < 0) {
            showToast("⚠️ Jumlah tidak valid");
            return;
        }

        try {
            const resCheck = await fetch(`/scan/check-stock/${productId}`);
            const dataCheck = await resCheck.json();
            if (!dataCheck.success)
                return showToast("⚠️ Produk tidak ditemukan");

            const stok = parseInt(dataCheck.stok, 10);
            if (qty > stok)
                return showToast(`⚠️ Jumlah melebihi stok tersedia (${stok})`);

            const resUpdate = await fetch("/scan/update", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrf,
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ product_id: productId, quantity: qty }),
            });

            const dataUpdate = await resUpdate.json();
            if (!dataUpdate.success)
                return showToast("⚠️ Gagal update jumlah produk");

            showToast("✅ Jumlah produk berhasil diperbarui", "success");
            loadCart();
        } catch (err) {
            console.error("Update qty error:", err);
            showToast("⚠️ Terjadi kesalahan saat update");
        }
    };

    /* ===============================
       🎥 INIT CAMERA FULLSCREEN PORTRAIT
    =============================== */
    try {
        const devices = await BrowserMultiFormatReader.listVideoInputDevices();
        if (devices.length === 0) {
            alert("Tidak ada kamera tersedia");
            return;
        }

        const isMobile = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);

        // Pilih kamera belakang jika ada
        let selectedDevice = devices[0];
        if (isMobile) {
            const backCamera = devices.find(
                (d) =>
                    d.label.toLowerCase().includes("back") ||
                    d.label.toLowerCase().includes("rear") ||
                    d.label.toLowerCase().includes("environment")
            );
            selectedDevice = backCamera || devices[0];
        }

        const constraints = {
            video: {
                deviceId: selectedDevice?.deviceId
                    ? { ideal: selectedDevice.deviceId }
                    : undefined,
                facingMode: isMobile ? "environment" : "user",
                width: { ideal: 1920 }, // resolusi tinggi agar jernih
                height: { ideal: 1080 },
                aspectRatio: 9 / 16,
                frameRate: { ideal: 60, max: 60 },
            },
        };

        const stream = await navigator.mediaDevices.getUserMedia(constraints);
        previewElem.srcObject = stream;
        currentTrack = stream.getVideoTracks()[0];

        // Start scanner
        codeReader.decodeFromStream(stream, previewElem, async (result) => {
            if (!result || hasScanned) return;

            hasScanned = true;
            const barcode = result.getText();

            if (!barcode || !/^\d{12,13}$/.test(barcode)) {
                navigator.vibrate?.(200);
                showToast("⚠️ Barcode harus 12 atau 13 digit", "error");
                setTimeout(() => (hasScanned = false), 500);
                return;
            }

            try {
                const res = await fetch("/scan", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrf,
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ barcode }),
                });

                const data = await res.json();
                if (!data.success || !data.product) {
                    navigator.vibrate?.(200);
                    showToast("❌ Produk tidak ditemukan", "error");
                    setTimeout(() => (hasScanned = false), 800);
                    return;
                }

                await fetch("/scan/add", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrf,
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({ product_id: data.product.id }),
                });

                showToast(`✅ ${data.product.nama} ditambahkan`, "success");
                new Audio("/sound/scan.mp3").play().catch(() => {});
                loadCart();
                setTimeout(() => (hasScanned = false), 1200);
            } catch (err) {
                console.error("Scan error:", err);
                navigator.vibrate?.(200);
                showToast("⚠️ Terjadi kesalahan saat scan", "error");
                hasScanned = false;
            }
        });

        console.log("✅ Camera portrait started successfully");

        // 🔦 FLASH (MOBILE)
        if (isMobile && flashBtn) {
            flashBtn.classList.remove("hidden");
            flashBtn.addEventListener("click", async () => {
                if (!currentTrack) return;
                const cap = currentTrack.getCapabilities();
                if (!cap.torch) return showToast("⚠️ Flash tidak didukung");

                torchOn = !torchOn;
                await currentTrack.applyConstraints({
                    advanced: [{ torch: torchOn }],
                });

                if (torchOn) {
                    // Flash aktif → warna kuning
                    flashBtn.classList.remove(
                        "bg-gradient-to-r",
                        "from-blue-600",
                        "to-cyan-500"
                    );
                    flashBtn.classList.add(
                        "bg-yellow-400",
                        "shadow-yellow-400/50"
                    );
                } else {
                    // Flash mati → kembali ke gradient biru
                    flashBtn.classList.remove(
                        "bg-yellow-400",
                        "shadow-yellow-400/50"
                    );
                    flashBtn.classList.add(
                        "bg-gradient-to-r",
                        "from-blue-600",
                        "to-cyan-500"
                    );
                }
            });
        }
    } catch (err) {
        console.error("Camera init error:", err);
        showToast("⚠️ Gagal mengakses kamera", "error");
    }

    // Load cart awal
    loadCart();
});