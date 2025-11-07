import { BrowserMultiFormatReader } from "@zxing/browser";

window.addEventListener("DOMContentLoaded", async () => {
    const previewElem = document.getElementById("preview");
    const flashBtn = document.getElementById("flash-toggle");
    if (!previewElem) return;

    const codeReader = new BrowserMultiFormatReader();
    let currentTrack = null;
    let torchOn = false;

    try {
        const devices = await BrowserMultiFormatReader.listVideoInputDevices();
        if (devices.length === 0) {
            alert("Tidak ada kamera yang tersedia.");
            return;
        }

        const isMobile = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
        let selectedDeviceId;
        let facingMode;

        if (isMobile) {
            const backCamera =
                devices.find((d) => d.label.toLowerCase().includes("back")) ||
                devices[0];
            selectedDeviceId = backCamera.deviceId;
            facingMode = "environment";
            console.log("📱 Mode: Mobile - Kamera Belakang");
        } else {
            const frontCamera =
                devices.find((d) => d.label.toLowerCase().includes("front")) ||
                devices[0];
            selectedDeviceId = frontCamera.deviceId;
            facingMode = "user";
            console.log("💻 Mode: Desktop - Kamera Depan");
        }

        const videoConstraints = {
            video: {
                deviceId: selectedDeviceId
                    ? { exact: selectedDeviceId }
                    : undefined,
                facingMode: facingMode,
                width: { ideal: 1920 },
                height: { ideal: 1080 },
                frameRate: { ideal: 30, max: 60 },
                focusMode: "continuous",
                advanced: [
                    { focusMode: "continuous" },
                    { exposureMode: "continuous" },
                    { whiteBalanceMode: "continuous" },
                ],
            },
        };

        let hasScanned = false;
        previewElem.style.transform = "scaleX(1)";

        console.log("🎥 Menginisialisasi kamera...");
        codeReader.decodeFromConstraints(
            videoConstraints,
            previewElem,
            async (result, err) => {
                if (!currentTrack && previewElem.srcObject) {
                    currentTrack = previewElem.srcObject.getVideoTracks()[0];
                }

                if (hasScanned) return;

                if (result) {
                    hasScanned = true;
                    const barcode = result.getText();

                    const csrfToken = document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content");

                    try {
                        const res = await fetch("/scan", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": csrfToken,
                                "Content-Type": "application/json",
                            },
                            body: JSON.stringify({ barcode }),
                        });

                        const data = await res.json();

                        if (data.success && data.product) {
                            // 🔊 Putar suara scan
                            const scanAudio = new Audio("/sound/scan.mp3"); // pastikan file ada di public/sound/scan.mp3
                            scanAudio
                                .play()
                                .catch((err) =>
                                    console.warn("Audio gagal diputar:", err)
                                );

                            const addRes = await fetch("/scan/add", {
                                method: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": csrfToken,
                                    "Content-Type": "application/json",
                                },
                                body: JSON.stringify({
                                    product_id: data.product.id,
                                }),
                            });

                            const addData = await addRes.json();
                            console.log("📦 Scan Add Response:", addData);

                            const stream = previewElem.srcObject;
                            if (stream) {
                                stream
                                    .getTracks()
                                    .forEach((track) => track.stop());
                                previewElem.srcObject = null;
                            }

                            if (addData.success) {
                                const posMeta = document.querySelector(
                                    'meta[name="pos-url"]'
                                );
                                const posUrl = posMeta?.getAttribute("content");
                                if (!posUrl) {
                                    alert("URL halaman POS tidak ditemukan.");
                                    return;
                                }
                                window.location.href = posUrl;
                            } else {
                                alert(
                                    addData.message ||
                                        "Gagal menambahkan ke keranjang."
                                );
                                hasScanned = false;
                            }
                        } else {
                            alert(data.message || "Produk tidak ditemukan.");
                            hasScanned = false;
                        }
                    } catch (err) {
                        console.error("Kesalahan fetch:", err);
                        alert("Terjadi kesalahan saat memproses barcode.");
                        hasScanned = false;
                    }
                }
            }
        );

        // 🔦 Tombol Flash hanya aktif di perangkat mobile
        if (isMobile && flashBtn) {
            flashBtn.addEventListener("click", async () => {
                try {
                    if (!currentTrack && previewElem.srcObject) {
                        currentTrack =
                            previewElem.srcObject.getVideoTracks()[0];
                    }

                    if (!currentTrack) {
                        alert("Kamera belum siap.");
                        return;
                    }

                    const capabilities = currentTrack.getCapabilities();
                    if (!capabilities.torch) {
                        alert("Torch tidak didukung di perangkat ini.");
                        return;
                    }

                    torchOn = !torchOn;
                    await currentTrack.applyConstraints({
                        advanced: [{ torch: torchOn }],
                    });

                    flashBtn.textContent = torchOn
                        ? "Matikan Flash 🔦"
                        : "Nyalakan Flash 💡";
                    flashBtn.classList.toggle("bg-yellow-500", torchOn);
                    flashBtn.classList.toggle("bg-gray-800/80", !torchOn);
                } catch (err) {
                    console.error("Gagal mengubah torch:", err);
                    alert("Gagal mengubah status flash kamera.");
                }
            });
        }
    } catch (error) {
        console.error("Kamera error:", error);
        alert("Gagal mengakses kamera.");
    }
});
