// ==========================================
// Page-Specific Inline Scripts (Auto-Merged)
// ==========================================
document.addEventListener("DOMContentLoaded", function () {
  // 1. window.PRAYER_WIDGET_CONFIG (moved from inline)
  if (!window.PRAYER_WIDGET_CONFIG) {
    window.PRAYER_WIDGET_CONFIG = {
      enabled: true,
      apiBase: "https://api.aladhan.com/v1",
      method: 3,
      school: 1,
      floatingPosition: "left",
      use24Hour: false,
      autoRequestLocation: true,
      highAccuracy: false,
      geoTimeoutMs: 20000,
      geoMaximumAgeMs: 300000,
      refreshIntervalMinutes: 15,
      fallbackCoords: { latitude: 23.8103, longitude: 90.4125 }, // ঢাকা
      fallbackCity: "ঢাকা (ডিফল্ট)",
      labels: {
        nextPrayerPrefix: "পরবর্তী নামাজ",
        locationPending: "লোকেশন অনুমতির অপেক্ষায়...",
        locationDenied: "লোকেশন অনুমতি না দিলে এটি কাজ করবে না",
        locationUnavailable: "এই ব্রাউজারে লোকেশন সাপোর্ট নেই",
        loading: "তথ্য লোড হচ্ছে...",
        fetchError: "নামাজের সময় লোড করা যায়নি",
        prayerTimesTitle: "আজকের নামাজের সময়",
        nextPrayerText: "পরবর্তী নামাজ",
        sehri: "সাহরীর শেষ সময়",
        iftar: "ইফতার",
        tomorrowFajr: "আগামীকালের ফজর",
      },
    };
  }

  // 2. Admission Info - FAQ Accordion
  const faqs = document.querySelectorAll(".adm-faq-item");
  if (faqs.length > 0) {
    faqs.forEach((item) => {
      item.addEventListener("toggle", function () {
        if (this.open) {
          document.querySelectorAll(".adm-faq-item[open]").forEach((other) => {
            if (other !== this) other.open = false;
          });
        }
      });
    });
  }

  // 3. Admission Info - Countdown
  const countdownEl = document.getElementById("countdown");
  if (countdownEl) {
    function updateCountdown() {
      const deadline = new Date("2026-03-31T23:59:59");
      const now = new Date();
      const diff = deadline - now;
      if (diff <= 0) {
        countdownEl.textContent = "শেষ হয়েছে";
        return;
      }
      const days = Math.floor(diff / 86400000);
      const hours = Math.floor((diff % 86400000) / 3600000);
      const mins = Math.floor((diff % 3600000) / 60000);
      countdownEl.textContent = days + " দিন " + hours + " ঘণ্টা " + mins + " মিনিট";
    }
    updateCountdown();
    setInterval(updateCountdown, 60000);
  }

  // 4. Book Archive - Price Range Slider & Mini Cart
  var slider = document.getElementById("priceRangeSlider");
  var maxLabel = document.getElementById("priceMax");
  if (slider && maxLabel) {
    slider.addEventListener("input", function () {
      maxLabel.textContent = slider.value;
    });
  }

  function syncMiniCart() {
    var content = document.getElementById("miniCartContent");
    var footer = document.getElementById("miniCartFooter");
    var totalEl = document.getElementById("miniCartTotal");
    if (!content) return;
    try {
      var cart = JSON.parse(localStorage.getItem("hd_cart") || "[]");
      if (!cart.length) {
        content.innerHTML = '<p class="mini-cart-empty">কার্টে কোনো বই নেই।</p>';
        if (footer) footer.style.display = "none";
        return;
      }
      var total = 0;
      var html = '<ul class="mini-cart-list">';
      cart.forEach(function (item) {
        total += (item.price || 0) * (item.quantity || 1);
        html += '<li class="mini-cart-item">' + '<span class="mini-cart-item-title">' + item.title + "</span>" + '<span class="mini-cart-item-qty">×' + (item.quantity || 1) + "</span>" + '<span class="mini-cart-item-price">৳ ' + (item.price || 0) * (item.quantity || 1) + "</span>" + "</li>";
      });
      html += "</ul>";
      content.innerHTML = html;
      if (totalEl) totalEl.textContent = "৳ " + total;
      if (footer) footer.style.display = "";
    } catch (e) {}
  }
  syncMiniCart();
  window.addEventListener("hd_cart_updated", syncMiniCart);

  // 5. Cart Page - Rendering
  var cartTableBody = document.getElementById("cartTableBody");
  if (cartTableBody) {
    var DELIVERY_CHARGE = 60;
    var discount = 0;

    function getCart() {
      try {
        return JSON.parse(localStorage.getItem("hd_cart") || "[]");
      } catch (e) {
        return [];
      }
    }

    function saveCart(cart) {
      localStorage.setItem("hd_cart", JSON.stringify(cart));
      window.dispatchEvent(new Event("hd_cart_updated"));
    }

    function formatPrice(n) {
      return "৳ " + n;
    }

    function renderCart() {
      var cart = getCart();
      var emptyState = document.getElementById("cartEmptyState");
      var hasItems = document.getElementById("cartHasItems");
      if (!cart.length) {
        if (emptyState) emptyState.style.display = "";
        if (hasItems) hasItems.style.display = "none";
        return;
      }
      if (emptyState) emptyState.style.display = "none";
      if (hasItems) hasItems.style.display = "";

      var subtotal = cart.reduce(function (sum, item) {
        return sum + (item.price || 0) * (item.quantity || 1);
      }, 0);

      var tbody = document.getElementById("cartTableBody");
      if (!tbody) return;
      var html = "";
      cart.forEach(function (item, idx) {
        var sub = (item.price || 0) * (item.quantity || 1);
        html += '<div class="cart-table-row" data-idx="' + idx + '">' + '<div class="cart-td cart-td-product">' + '<div class="cart-product-info">' + '<div class="cart-product-cover"><span class="material-symbols-outlined">menu_book</span></div>' + '<div class="cart-product-meta"><strong>' + item.title + "</strong></div>" + "</div>" + "</div>" + '<div class="cart-td cart-td-price">' + formatPrice(item.price || 0) + "</div>" + '<div class="cart-td cart-td-qty">' + '<div class="sb-qty-stepper">' + '<button class="sb-qty-btn cart-qty-minus" data-idx="' + idx + '"><span class="material-symbols-outlined">remove</span></button>' + '<input type="number" class="sb-qty-input cart-qty-input" value="' + (item.quantity || 1) + '" min="1" max="20" data-idx="' + idx + '" />' + '<button class="sb-qty-btn cart-qty-plus" data-idx="' + idx + '"><span class="material-symbols-outlined">add</span></button>' + "</div>" + "</div>" + '<div class="cart-td cart-td-sub">' + formatPrice(sub) + "</div>" + '<div class="cart-td cart-td-remove">' + '<button class="cart-remove-btn" data-idx="' + idx + '" aria-label="সরান">' + '<span class="material-symbols-outlined">close</span>' + "</button>" + "</div>" + "</div>";
      });
      tbody.innerHTML = html;

      if (document.getElementById("cartSubtotal")) document.getElementById("cartSubtotal").textContent = formatPrice(subtotal);
      var afterDiscount = Math.max(0, subtotal - discount);
      var total = afterDiscount + DELIVERY_CHARGE;
      if (document.getElementById("cartDelivery")) document.getElementById("cartDelivery").textContent = formatPrice(DELIVERY_CHARGE);
      if (document.getElementById("cartGrandTotal")) document.getElementById("cartGrandTotal").textContent = formatPrice(total);

      if (discount > 0) {
        if (document.getElementById("cartDiscountRow")) document.getElementById("cartDiscountRow").style.display = "";
        if (document.getElementById("cartDiscount")) document.getElementById("cartDiscount").textContent = "- " + formatPrice(discount);
      } else {
        if (document.getElementById("cartDiscountRow")) document.getElementById("cartDiscountRow").style.display = "none";
      }

      tbody.querySelectorAll(".cart-qty-minus").forEach(function (btn) {
        btn.addEventListener("click", function () {
          var i = parseInt(btn.dataset.idx, 10);
          var cart2 = getCart();
          if ((cart2[i].quantity || 1) > 1) cart2[i].quantity--;
          else cart2[i].quantity = 1;
          saveCart(cart2);
          renderCart();
        });
      });
      tbody.querySelectorAll(".cart-qty-plus").forEach(function (btn) {
        btn.addEventListener("click", function () {
          var i = parseInt(btn.dataset.idx, 10);
          var cart2 = getCart();
          if ((cart2[i].quantity || 1) < 20) cart2[i].quantity = (cart2[i].quantity || 1) + 1;
          saveCart(cart2);
          renderCart();
        });
      });
      tbody.querySelectorAll(".cart-qty-input").forEach(function (inp) {
        inp.addEventListener("change", function () {
          var i = parseInt(inp.dataset.idx, 10);
          var val = Math.max(1, Math.min(20, parseInt(inp.value, 10) || 1));
          var cart2 = getCart();
          cart2[i].quantity = val;
          saveCart(cart2);
          renderCart();
        });
      });
      tbody.querySelectorAll(".cart-remove-btn").forEach(function (btn) {
        btn.addEventListener("click", function () {
          var i = parseInt(btn.dataset.idx, 10);
          var cart2 = getCart();
          cart2.splice(i, 1);
          saveCart(cart2);
          renderCart();
        });
      });
    }

    var clearBtn = document.getElementById("cartClearBtn");
    if (clearBtn) {
      clearBtn.addEventListener("click", function () {
        if (confirm("কার্ট খালি করবেন?")) {
          saveCart([]);
          renderCart();
        }
      });
    }

    var couponBtn = document.getElementById("couponApplyBtn");
    if (couponBtn) {
      couponBtn.addEventListener("click", function () {
        var code = document.getElementById("couponInput").value.trim().toUpperCase();
        var msg = document.getElementById("couponMsg");
        if (code === "HOQUER10") {
          var cart = getCart();
          var sub = cart.reduce(function (s, i) {
            return s + (i.price || 0) * (i.quantity || 1);
          }, 0);
          discount = Math.round(sub * 0.1);
          if (msg) {
            msg.textContent = "কুপন প্রয়োগ হয়েছে! ১০% ছাড় পেয়েছেন।";
            msg.style.color = "var(--primary-green-dark)";
          }
        } else {
          discount = 0;
          if (msg) {
            msg.textContent = "কুপন কোডটি বৈধ নয়।";
            msg.style.color = "#b91c1c";
          }
        }
        renderCart();
      });
    }

    renderCart();
    window.addEventListener("hd_cart_updated", renderCart);
    window.addEventListener("storage", function (e) {
      if (e.key === "hd_cart") renderCart();
    });
  }

  // 6. Checkout Page - Rendering
  var coDivision = document.getElementById("coDivision");
  if (coDivision) {
    var DELIVERY_CHARGE_CO = 60;
    var coDiscount = 0;

    var districtsByDivision = {
      dhaka: ["ঢাকা", "গাজীপুর", "নারায়ণগঞ্জ", "মানিকগঞ্জ", "মুন্সিগঞ্জ", "নরসিংদী", "কিশোরগঞ্জ", "ময়মনসিংহ (ঢাকা বিভাগের অধীন)", "ফরিদপুর", "মাদারীপুর", "গোপালগঞ্জ", "রাজবাড়ী", "শরিয়তপুর"],
      chittagong: ["চট্টগ্রাম", "কক্সবাজার", "ব্রাহ্মণবাড়িয়া", "কুমিল্লা", "চাঁদপুর", "ফেনী", "লক্ষ্মীপুর", "নোয়াখালী", "রাঙামাটি", "খাগড়াছড়ি", "বান্দরবান"],
      rajshahi: ["রাজশাহী", "চাঁপাইনবাবগঞ্জ", "নওগাঁ", "নাটোর", "পাবনা", "সিরাজগঞ্জ", "বগুড়া", "জয়পুরহাট"],
      khulna: ["খুলনা", "বাগেরহাট", "সাতক্ষীরা", "যশোর", "ঝিনাইদহ", "মাগুরা", "নড়াইল", "কুষ্টিয়া", "মেহেরপুর", "চুয়াডাঙ্গা"],
      barisal: ["বরিশাল", "পটুয়াখালী", "পিরোজপুর", "ঝালকাঠি", "বরগুনা", "ভোলা"],
      sylhet: ["সিলেট", "হবিগঞ্জ", "মৌলভীবাজার", "সুনামগঞ্জ"],
      mymensingh: ["ময়মনসিংহ", "নেত্রকোণা", "জামালপুর", "শেরপুর"],
      rangpur: ["রংপুর", "গাইবান্ধা", "কুড়িগ্রাম", "লালমনিরহাট", "নীলফামারী", "পঞ্চগড়", "ঠাকুরগাঁও", "দিনাজপুর"],
    };

    coDivision.addEventListener("change", function () {
      var div = this.value;
      var distSel = document.getElementById("coDistrict");
      if (distSel) distSel.innerHTML = '<option value="">জেলা বেছে নিন</option>';
      if (div && districtsByDivision[div] && distSel) {
        districtsByDivision[div].forEach(function (d) {
          var opt = document.createElement("option");
          opt.value = d;
          opt.textContent = d;
          distSel.appendChild(opt);
        });
      }
      DELIVERY_CHARGE_CO = div === "dhaka" ? 60 : 120;
      renderCoSummary();
    });

    document.querySelectorAll('[name="payment"]').forEach(function (radio) {
      radio.addEventListener("change", function () {
        document.querySelectorAll(".co-payment-fields").forEach(function (f) {
          f.style.display = "none";
        });
        document.querySelectorAll(".co-payment-card").forEach(function (c) {
          c.classList.remove("active");
        });
        var closest = radio.closest(".co-radio-card");
        if (closest) closest.classList.add("active");
        if (radio.value === "bkash" && document.getElementById("coFieldsBkash")) document.getElementById("coFieldsBkash").style.display = "";
        if (radio.value === "nagad" && document.getElementById("coFieldsNagad")) document.getElementById("coFieldsNagad").style.display = "";
        if (radio.value === "rocket" && document.getElementById("coFieldsRocket")) document.getElementById("coFieldsRocket").style.display = "";
      });
    });

    document.querySelectorAll('[name="delivery"]').forEach(function (radio) {
      radio.addEventListener("change", function () {
        document.querySelectorAll(".co-radio-cards .co-radio-card").forEach(function (c) {
          c.classList.remove("active");
        });
        var closest = radio.closest(".co-radio-card");
        if (closest) closest.classList.add("active");
        DELIVERY_CHARGE_CO = radio.value === "pickup" ? 0 : 60;
        renderCoSummary();
      });
    });

    function getCartCo() {
      try {
        return JSON.parse(localStorage.getItem("hd_cart") || "[]");
      } catch (e) {
        return [];
      }
    }

    function renderCoSummary() {
      var cart = getCartCo();
      var items = document.getElementById("coSummaryItems");
      var subtotal = cart.reduce(function (s, i) {
        return s + (i.price || 0) * (i.quantity || 1);
      }, 0);

      if (items) {
        if (!cart.length) {
          items.innerHTML = '<p style="color:var(--text-light);font-size:14px;">কার্ট খালি</p>';
        } else {
          var html = "";
          cart.forEach(function (item) {
            html += '<div class="co-summary-item">' + '<span class="co-summary-item-title">' + item.title + " <em>×" + (item.quantity || 1) + "</em></span>" + '<span class="co-summary-item-price">৳ ' + (item.price || 0) * (item.quantity || 1) + "</span>" + "</div>";
          });
          items.innerHTML = html;
        }
      }

      var afterDiscount = Math.max(0, subtotal - coDiscount);
      var total = afterDiscount + DELIVERY_CHARGE_CO;
      if (document.getElementById("coSubtotal")) document.getElementById("coSubtotal").textContent = "৳ " + subtotal;
      if (document.getElementById("coDelivery")) document.getElementById("coDelivery").textContent = "৳ " + DELIVERY_CHARGE_CO;
      if (document.getElementById("coTotal")) document.getElementById("coTotal").textContent = "৳ " + total;
      if (coDiscount > 0) {
        if (document.getElementById("coDiscountRow")) document.getElementById("coDiscountRow").style.display = "";
        if (document.getElementById("coDiscount")) document.getElementById("coDiscount").textContent = "- ৳ " + coDiscount;
      } else {
        if (document.getElementById("coDiscountRow")) document.getElementById("coDiscountRow").style.display = "none";
      }
    }

    var coCouponApply = document.getElementById("coCouponApply");
    if (coCouponApply) {
      coCouponApply.addEventListener("click", function () {
        var code = document.getElementById("coCouponInput").value.trim().toUpperCase();
        var msg = document.getElementById("coCouponMsg");
        var cart = getCartCo();
        var sub = cart.reduce(function (s, i) {
          return s + (i.price || 0) * (i.quantity || 1);
        }, 0);
        if (code === "HOQUER10") {
          coDiscount = Math.round(sub * 0.1);
          if (msg) {
            msg.textContent = "কুপন প্রয়োগ হয়েছে! ১০% ছাড় পেয়েছেন।";
            msg.style.color = "var(--primary-green-dark)";
          }
        } else {
          coDiscount = 0;
          if (msg) {
            msg.textContent = "কুপন কোডটি বৈধ নয়।";
            msg.style.color = "#b91c1c";
          }
        }
        renderCoSummary();
      });
    }

    var checkoutForm = document.getElementById("checkoutForm");
    if (checkoutForm) {
      checkoutForm.addEventListener("submit", function (e) {
        e.preventDefault();
        var coTerms = document.getElementById("coTerms");
        if (coTerms && !coTerms.checked) {
          alert("অর্ডার দিতে শর্তাবলী মেনে নিন।");
          return;
        }
        alert("অর্ডার সফলভাবে সম্পন্ন হয়েছে! আমরা শীঘ্রই আপনার সাথে যোগাযোগ করব।");
        localStorage.removeItem("hd_cart");
        window.dispatchEvent(new Event("hd_cart_updated"));
        window.location.href = "index.html";
      });
    }

    renderCoSummary();
    window.addEventListener("hd_cart_updated", renderCoSummary);
    window.addEventListener("storage", function (e) {
      if (e.key === "hd_cart") renderCoSummary();
    });
  }

  // 7. Tab Filters (Dini Jiggasa / Notice)
  document.querySelectorAll(".jiggasa-tab").forEach((btn) => {
    btn.addEventListener("click", function () {
      var tabContainer = this.closest(".jiggasa-tabs-container") || this.parentElement;
      tabContainer.querySelectorAll(".jiggasa-tab").forEach((b) => b.classList.remove("active"));
      this.classList.add("active");

      const tab = this.dataset.tab;

      // For Dini Jiggasa
      if (document.querySelectorAll(".jiggasa-card").length > 0 && !document.querySelector(".notice-list")) {
        document.querySelectorAll(".jiggasa-card").forEach((card) => {
          if (tab === "all") {
            card.style.display = "";
          } else if (tab === "answered") {
            card.style.display = card.classList.contains("answered") ? "" : "none";
          } else {
            card.style.display = card.classList.contains("pending") ? "" : "none";
          }
        });
      }

      // For Notice
      const allCards = document.querySelectorAll(".notice-list .notice-card, .notice-pinned-section .notice-card");
      if (allCards.length > 0) {
        allCards.forEach((card) => {
          if (tab === "all") {
            card.style.display = "";
          } else if (tab === "urgent") {
            card.style.display = card.classList.contains("urgent") ? "" : "none";
          } else if (tab === "general") {
            card.style.display = card.classList.contains("general") ? "" : "none";
          } else {
            card.style.display = "none";
          }
        });
      }
    });
  });

  // 8. Q&A / Doa Tabs
  const qdTabs = document.querySelectorAll(".qdform-tab");
  if (qdTabs.length > 0) {
    qdTabs.forEach((tab) => {
      tab.addEventListener("click", function () {
        qdTabs.forEach((t) => t.classList.remove("active"));
        this.classList.add("active");

        document.querySelectorAll(".qdform-panel").forEach((p) => p.classList.remove("active"));

        const target = this.getAttribute("data-tab");
        const panel = document.getElementById("panel-" + target);
        if (panel) panel.classList.add("active");
      });
    });
  }

  // 9. Single Book Gallery & Lightbox
  const sbMainImg = document.getElementById("sbMainImg");
  const sbThumbs = document.querySelectorAll(".sb-thumb");
  const sbMainCover = document.querySelector(".sb-main-cover");

  if (sbMainImg && sbThumbs.length > 0) {
    sbThumbs.forEach((thumb) => {
      thumb.addEventListener("click", function () {
        sbThumbs.forEach((t) => t.classList.remove("active"));
        this.classList.add("active");
        const newSrc = this.getAttribute("data-src");
        if (newSrc) sbMainImg.src = newSrc;
      });
    });
  }

  const sbLightbox = document.getElementById("sbLightbox");
  const sbLightboxImg = document.getElementById("sbLightboxImg");
  const sbLightboxClose = document.getElementById("sbLightboxClose");
  const sbLightboxPrev = document.getElementById("sbLightboxPrev");
  const sbLightboxNext = document.getElementById("sbLightboxNext");

  if (sbMainCover && sbLightbox && sbLightboxImg) {
    let currentImgIndex = 0;

    const updateLightboxImage = (index) => {
      if (sbThumbs[index]) {
        const newSrc = sbThumbs[index].getAttribute("data-src");
        if (newSrc) {
          sbLightboxImg.src = newSrc;
          currentImgIndex = index;
        }
      }
    };

    sbMainCover.addEventListener("click", function () {
      // Find current active thumb index
      sbThumbs.forEach((thumb, idx) => {
        if (thumb.classList.contains("active")) {
          currentImgIndex = idx;
        }
      });
      updateLightboxImage(currentImgIndex);
      sbLightbox.classList.add("active");
      document.body.style.overflow = "hidden";
    });

    if (sbLightboxPrev) {
      sbLightboxPrev.addEventListener("click", function (e) {
        e.stopPropagation();
        let nextIdx = currentImgIndex - 1;
        if (nextIdx < 0) nextIdx = sbThumbs.length - 1;
        updateLightboxImage(nextIdx);
      });
    }

    if (sbLightboxNext) {
      sbLightboxNext.addEventListener("click", function (e) {
        e.stopPropagation();
        let nextIdx = currentImgIndex + 1;
        if (nextIdx >= sbThumbs.length) nextIdx = 0;
        updateLightboxImage(nextIdx);
      });
    }

    const closeLightbox = function () {
      sbLightbox.classList.remove("active");
      document.body.style.overflow = "auto";
    };

    if (sbLightboxClose) sbLightboxClose.addEventListener("click", closeLightbox);
    sbLightbox.addEventListener("click", function (e) {
      if (e.target === sbLightbox) closeLightbox();
    });

    document.addEventListener("keydown", function (e) {
      if (!sbLightbox.classList.contains("active")) return;
      if (e.key === "Escape") {
        closeLightbox();
      } else if (e.key === "ArrowLeft") {
        sbLightboxPrev.click();
      } else if (e.key === "ArrowRight") {
        sbLightboxNext.click();
      }
    });
  }
  // 10. Photo Gallery Lightbox
  const lightbox = document.getElementById("lightbox");
  const lightboxImg = document.getElementById("lightboxImg");
  const lightboxCaption = document.getElementById("lightboxCaption");
  const lightboxCounter = document.getElementById("lightboxCounter");
  const lightboxDl = document.getElementById("lightboxDl");
  const lightboxPrev = document.getElementById("lightboxPrev");
  const lightboxNext = document.getElementById("lightboxNext");
  const lightboxClose = document.getElementById("lightboxClose");
  const lightboxBackdrop = document.getElementById("lightboxBackdrop");
  const photoItems = document.querySelectorAll(".gallery-photo-item");

  if (lightbox && photoItems.length > 0) {
    let currentIndex = 0;

    const toBanglaDigitsLocal = (str) => {
      const banglaDigits = ["০", "১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯"];
      return String(str).replace(/\d/g, (d) => banglaDigits[d]);
    };

    const updateLightbox = (index) => {
      const item = photoItems[index];
      const img = item.querySelector("img");
      const caption = item.getAttribute("data-caption");
      const date = item.getAttribute("data-date");
      const src = img.src.split("?")[0]; // Use base image for lightbox

      lightboxImg.src = src;
      lightboxCaption.innerHTML = `<strong>${caption}</strong><br><small style="opacity:0.7">${date}</small>`;
      lightboxCounter.textContent = `${toBanglaDigitsLocal(index + 1)} / ${toBanglaDigitsLocal(photoItems.length)}`;
      if (lightboxDl) lightboxDl.href = src;
      currentIndex = index;
    };

    photoItems.forEach((item, index) => {
      item.addEventListener("click", () => {
        updateLightbox(index);
        lightbox.hidden = false;
        document.body.style.overflow = "hidden";
      });
    });

    const closeLightbox = () => {
      lightbox.hidden = true;
      document.body.style.overflow = "";
    };

    if (lightboxClose) lightboxClose.addEventListener("click", closeLightbox);
    if (lightboxBackdrop) lightboxBackdrop.addEventListener("click", closeLightbox);

    if (lightboxPrev) {
      lightboxPrev.addEventListener("click", () => {
        let prevIndex = currentIndex - 1;
        if (prevIndex < 0) prevIndex = photoItems.length - 1;
        updateLightbox(prevIndex);
      });
    }

    if (lightboxNext) {
      lightboxNext.addEventListener("click", () => {
        let nextIndex = currentIndex + 1;
        if (nextIndex >= photoItems.length) nextIndex = 0;
        updateLightbox(nextIndex);
      });
    }

    document.addEventListener("keydown", (e) => {
      if (lightbox.hidden) return;
      if (e.key === "Escape") closeLightbox();
      if (e.key === "ArrowLeft") lightboxPrev.click();
      if (e.key === "ArrowRight") lightboxNext.click();
    });
  }

  // 11. Probondho Font Size Control
  const fontBtns = document.querySelectorAll(".probondho-font-btn");
  const articleBody = document.getElementById("articleBody");
  if (fontBtns.length > 0 && articleBody) {
    fontBtns.forEach((btn) => {
      btn.addEventListener("click", function () {
        fontBtns.forEach((b) => b.classList.remove("active"));
        this.classList.add("active");
        const size = this.dataset.size;
        if (size === "small") {
          articleBody.style.fontSize = "16px";
        } else if (size === "medium") {
          articleBody.style.fontSize = "18px";
        } else if (size === "large") {
          articleBody.style.fontSize = "22px";
        }
      });
    });
  }
}); // ==========================================
// Main JavaScript
// ==========================================

document.addEventListener("DOMContentLoaded", function () {
  // Smooth Scrolling for anchor links
  const links = document.querySelectorAll('a[href^="#"]');
  links.forEach((link) => {
    link.addEventListener("click", function (e) {
      const href = this.getAttribute("href");
      if (href !== "#") {
        e.preventDefault();
        const target = document.querySelector(href);
        if (target) {
          target.scrollIntoView({
            behavior: "smooth",
            block: "start",
          });
        }
      }
    });
  });

  // Scroll to top button
  let scrollTopBtn = document.createElement("button");
  scrollTopBtn.textContent = "\u2191";
  scrollTopBtn.className = "scroll-top-btn";
  scrollTopBtn.style.cssText = `
        position: fixed;
        bottom: 30px;
        right: 30px;
        background: #0f6b3f;
        color: white;
        border: none;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        font-size: 24px;
        cursor: pointer;
        display: none;
        z-index: 1000;
        box-shadow: 0 4px 14px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
    `;

  document.body.appendChild(scrollTopBtn);

  // Show/hide scroll to top button
  window.addEventListener("scroll", function () {
    if (window.pageYOffset > 300) {
      scrollTopBtn.style.display = "block";
    } else {
      scrollTopBtn.style.display = "none";
    }
  });

  scrollTopBtn.addEventListener("click", function () {
    window.scrollTo({
      top: 0,
      behavior: "smooth",
    });
  });

  scrollTopBtn.addEventListener("mouseenter", function () {
    this.style.transform = "scale(1.1)";
    this.style.background = "#168a54";
  });

  scrollTopBtn.addEventListener("mouseleave", function () {
    this.style.transform = "scale(1)";
    this.style.background = "#0f6b3f";
  });

  // Animation on scroll (Reveal Effect)
  const observerOptions = {
    threshold: 0.1,
    rootMargin: "0px 0px -50px 0px",
  };

  const observer = new IntersectionObserver(function (entries) {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("reveal-active");
      }
    });
  }, observerOptions);

  // Elements to reveal
  const revealElements = document.querySelectorAll(".card, .glass-card, .section-title, .section-intro, .promo-wide-wrapper, .bridge-cta-shell, .about-intro, .current-issue, .special-issue");

  revealElements.forEach((el) => {
    el.classList.add("reveal-item");
    observer.observe(el);
  });

  // Navigation Logic
  const menuToggle = document.getElementById("mobile-menu");
  const navWrap = document.querySelector(".nav-wrap");
  const siteNav = document.getElementById("site-navigation");
  const navOverlay = document.querySelector(".nav-overlay");
  const closeMenuBtn = document.querySelector(".close-menu");
  const navItems = document.querySelectorAll(".nav-item");

  const closeMenu = () => {
    if (menuToggle) menuToggle.classList.remove("active");
    if (navWrap) navWrap.classList.remove("active");
    if (siteNav) siteNav.classList.remove("active");
    if (navOverlay) navOverlay.classList.remove("active");
    document.body.classList.remove("menu-open");
    document.body.style.overflow = ""; // Enable scrolling
  };

  const openMenu = () => {
    if (menuToggle) menuToggle.classList.add("active");
    if (navWrap) navWrap.classList.add("active");
    if (siteNav) siteNav.classList.add("active");
    if (navOverlay) navOverlay.classList.add("active");
    document.body.classList.add("menu-open");
    document.body.style.overflow = "hidden"; // Disable scrolling
  };

  if (menuToggle) {
    menuToggle.addEventListener("click", function () {
      if (navWrap && navWrap.classList.contains("active")) {
        closeMenu();
      } else {
        openMenu();
      }
    });
  }

  if (closeMenuBtn) {
    closeMenuBtn.addEventListener("click", closeMenu);
  }

  // Close menu when clicking on the overlay
  if (navOverlay) {
    navOverlay.addEventListener("click", closeMenu);
  }


  navItems.forEach((item) => {
    const dropdown = item.querySelector(".dropdown-menu");
    if (!dropdown) return;

    // Ensure has-dropdown class is present (fallback for dynamic menus)
    item.classList.add("has-dropdown");

    // ── Mobile: inject a dedicated arrow toggle button ──────────────────────
    // This avoids capturing the parent <a> click, so parent links with
    // real URLs still navigate normally on desktop.
    const existingBtn = item.querySelector(".dd-toggle-btn");
    if (!existingBtn) {
      const toggleBtn = document.createElement("button");
      toggleBtn.className = "dd-toggle-btn";
      toggleBtn.setAttribute("aria-label", "সাব-মেনু খুলুন");
      toggleBtn.setAttribute("aria-expanded", "false");
      toggleBtn.innerHTML = '<span class="material-symbols-outlined">expand_more</span>';

      // Insert the toggle button right after the top-level <a>
      const topLink = item.querySelector(":scope > a");
      if (topLink) {
        topLink.insertAdjacentElement("afterend", toggleBtn);
      }

      toggleBtn.addEventListener("click", function (e) {
        e.stopPropagation();

        if (window.innerWidth > 992) return; // Desktop uses CSS hover

        // Save scroll position
        const navMenu = document.querySelector(".nav-menu");
        const savedScrollTop = navMenu ? navMenu.scrollTop : 0;

        const isOpen = item.classList.contains("active");

        // Close all other dropdowns first
        navItems.forEach((other) => {
          if (other !== item) {
            other.classList.remove("active");
            const otherBtn = other.querySelector(".dd-toggle-btn");
            if (otherBtn) otherBtn.setAttribute("aria-expanded", "false");
          }
        });

        // Toggle current
        item.classList.toggle("active", !isOpen);
        toggleBtn.setAttribute("aria-expanded", String(!isOpen));

        // Restore scroll
        if (navMenu) {
          navMenu.scrollTop = savedScrollTop;
          requestAnimationFrame(() => {
            navMenu.scrollTop = savedScrollTop;
          });
        }
      });
    }
  });

  // ── Desktop: close open dropdowns when clicking outside nav ─────────────
  document.addEventListener("click", function (e) {
    if (!e.target.closest("#site-navigation")) {
      navItems.forEach((item) => {
        item.classList.remove("active");
        const btn = item.querySelector(".dd-toggle-btn");
        if (btn) btn.setAttribute("aria-expanded", "false");
      });
    }
  });


  // Dynamic Video Modal Logic (Robust Fix for Error 153)
  const videoThumbs = document.querySelectorAll(".video-thumb");
  const videoModal = document.getElementById("video-modal");
  const videoPlaceholder = document.getElementById("video-placeholder");
  const closeModal = document.querySelector(".close-modal");

  if (videoThumbs && videoModal && videoPlaceholder) {
    videoThumbs.forEach((thumb) => {
      thumb.addEventListener("click", function () {
        const videoId = this.getAttribute("data-video-id");
        if (videoId) {
          // Create iframe dynamically with origin parameter for better compatibility
          const origin = window.location.origin === "null" ? "*" : window.location.origin;
          const iframe = document.createElement("iframe");
          iframe.src = `https://www.youtube.com/embed/${videoId}?rel=0&origin=${origin}`;
          iframe.setAttribute("frameborder", "0");
          iframe.setAttribute("allowfullscreen", "true");
          iframe.setAttribute("allow", "autoplay; encrypted-media; picture-in-picture");

          // Clear placeholder and add new iframe
          videoPlaceholder.innerHTML = "";
          videoPlaceholder.appendChild(iframe);

          videoModal.classList.add("active");
          document.body.style.overflow = "hidden";
        }
      });
    });

    // In-Place Video Logic (Added)
    const inplaceVideos = document.querySelectorAll(".video-inplace");
    if (inplaceVideos) {
      inplaceVideos.forEach((wrapper) => {
        wrapper.addEventListener("click", function () {
          const videoId = this.getAttribute("data-video-id");
          if (videoId) {
            // Check if iframe already exists to prevent re-creation
            if (this.querySelector("iframe")) return;

            const origin = window.location.origin === "null" ? "*" : window.location.origin;
            const iframe = document.createElement("iframe");
            iframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0&origin=${origin}`; // Autoplay enabled
            iframe.setAttribute("frameborder", "0");
            iframe.setAttribute("allowfullscreen", "true");
            iframe.setAttribute("allow", "autoplay; encrypted-media; picture-in-picture");

            // Clear existing content (overlay, cover, etc.) and append iframe
            this.innerHTML = "";
            this.appendChild(iframe);
          }
        });
      });
    }

    const closeVideoModal = function () {
      videoModal.classList.remove("active");
      videoPlaceholder.innerHTML = ""; // Completely destroy the player instance
      document.body.style.overflow = "auto";
    };

    if (closeModal) {
      closeModal.addEventListener("click", closeVideoModal);
    }

    videoModal.addEventListener("click", function (e) {
      if (e.target === videoModal) {
        closeVideoModal();
      }
    });

    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && videoModal.classList.contains("active")) {
        closeVideoModal();
      }
    });
  }

  // ==========================================
  // Media Tabs (Video / Audio)
  // ==========================================
  const mediaTabBtns = document.querySelectorAll(".media-tab-btn");
  const mediaTabContents = document.querySelectorAll(".media-tab-content");

  mediaTabBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      const targetTab = btn.getAttribute("data-tab");

      // Remove active from all buttons and contents
      mediaTabBtns.forEach((b) => b.classList.remove("active"));
      mediaTabContents.forEach((c) => c.classList.remove("active"));

      // Add active to clicked button and target content
      btn.classList.add("active");
      const targetContent = document.getElementById("tab-" + targetTab);
      if (targetContent) {
        targetContent.classList.add("active");
      }
    });
  });

  // ==========================================
  // Audio Players (Generic logic for all instances)
  // ==========================================
  window.initAudioPlayers = function() {
    const audioPlayerContainers = document.querySelectorAll(".audio-player:not(.initialized)");
    
    audioPlayerContainers.forEach((container) => {
      container.classList.add("initialized");
      const playerID = container.id;
      let playBtn = document.querySelector(`[data-player-target="${playerID}"]`);

      if (!playBtn && playerID === "featured-audio-player") {
        playBtn = document.getElementById("featured-audio-play-btn");
      }

      const playerPlayPause = container.querySelector(".player-btn-main");
      const playerSeek = container.querySelector(".player-seek");
      const playerCurrent = container.querySelector(".player-current");
      const playerDuration = container.querySelector(".player-duration");
      const playerBackward = container.querySelector(".player-btn-backward");
      const playerForward = container.querySelector(".player-btn-forward");

      const audioSrc = container.dataset.src;
      if (!audioSrc) return;

      const audio = document.createElement("audio");
      audio.preload = "none";
      audio.src = encodeURI(audioSrc);
      container._hdAudio = audio; // Store for global access

      let isSeeking = false;
      let savedTime = 0;
      let isMetadataLoaded = false;

      if (playerCurrent) playerCurrent.textContent = "0:00";
      if (playerDuration) playerDuration.textContent = "--:--";

      audio.addEventListener("play", () => {
        document.querySelectorAll('audio').forEach(a => {
          if (a !== audio && !a.paused) a.pause();
        });
      });

      if (playBtn) {
        playBtn.addEventListener("click", function (e) {
          e.preventDefault();
          container.classList.add("active");
          if (!audio.src || audio.src === window.location.href) {
            audio.src = encodeURI(audioSrc);
            audio.load();
            if (savedTime > 0) audio.currentTime = savedTime;
          }
          audio.play();
          this.style.display = "none";
        });
      }

      if (playerPlayPause) {
        audio.onplay = () => { playerPlayPause.querySelector(".material-symbols-outlined").textContent = "pause"; };
        audio.onpause = () => {
          playerPlayPause.querySelector(".material-symbols-outlined").textContent = "play_arrow";
          if (!isSeeking && audio.currentTime > 0) {
            savedTime = audio.currentTime;
            audio.removeAttribute("src");
            audio.load();
          }
        };

        playerPlayPause.addEventListener("click", function (e) {
          e.preventDefault();
          if (audio.paused || !audio.src || audio.src === window.location.href) {
            if (!audio.src || audio.src === window.location.href) {
              audio.src = encodeURI(audioSrc);
              audio.load();
              if (savedTime > 0) {
                audio.addEventListener("loadedmetadata", function onMeta() {
                  audio.currentTime = savedTime;
                  audio.removeEventListener("loadedmetadata", onMeta);
                  audio.play();
                });
              } else { audio.play(); }
            } else { audio.play(); }
          } else { audio.pause(); }
        });
      }

      audio.onloadedmetadata = function () {
        isMetadataLoaded = true;
        if (playerDuration) playerDuration.textContent = formatTime(audio.duration);
        if (playerSeek) {
          playerSeek.max = Math.floor(audio.duration);
          if (!isSeeking) playerSeek.value = Math.floor(audio.currentTime);
          playerSeek.oninput = () => { isSeeking = true; };
          playerSeek.onchange = () => {
            savedTime = Number(playerSeek.value);
            if (!audio.src || audio.src === window.location.href) {
              audio.src = encodeURI(audioSrc);
              audio.load();
            }
            audio.currentTime = savedTime;
            if (playerCurrent) playerCurrent.textContent = formatTime(savedTime);
            audio.play();
            isSeeking = false;
          };
        }
      };

      audio.ontimeupdate = function () {
        if (!isMetadataLoaded || isNaN(audio.duration)) return;
        if (playerSeek && !isSeeking) playerSeek.value = Math.floor(audio.currentTime);
        if (playerCurrent) playerCurrent.textContent = formatTime(audio.currentTime);
      };

      if (playerBackward) {
        playerBackward.addEventListener("click", (e) => {
          e.preventDefault();
          let nt = Math.max(0, (audio.currentTime || savedTime) - 10);
          savedTime = nt;
          if (audio.src && audio.src !== window.location.href) audio.currentTime = nt;
          if (playerSeek) playerSeek.value = Math.floor(nt);
          if (playerCurrent) playerCurrent.textContent = formatTime(nt);
        });
      }

      if (playerForward) {
        playerForward.addEventListener("click", (e) => {
          e.preventDefault();
          if (isMetadataLoaded && isFinite(audio.duration)) {
            let nt = Math.min(audio.duration, (audio.currentTime || savedTime) + 10);
            savedTime = nt;
            if (audio.src && audio.src !== window.location.href) audio.currentTime = nt;
            if (playerSeek) playerSeek.value = Math.floor(nt);
            if (playerCurrent) playerCurrent.textContent = formatTime(nt);
          }
        });
      }

      audio.oncanplaythrough = () => { if (playerSeek) playerSeek.disabled = false; };
      audio.onended = () => {
        if (playerSeek) playerSeek.value = 0;
        if (playerCurrent) playerCurrent.textContent = "0:00";
        savedTime = 0;
        playerPlayPause.querySelector(".material-symbols-outlined").textContent = "play_arrow";
      };
    });
  };

  function formatTime(secs) {
    let ss = Math.floor(secs);
    let hh = Math.floor(ss / 3600);
    let mm = Math.floor((ss - hh * 3600) / 60);
    ss = ss - hh * 3600 - mm * 60;
    if (hh > 0) mm = mm < 10 ? "0" + mm : mm;
    ss = ss < 10 ? "0" + ss : ss;
    return hh > 0 ? hh + ":" + mm + ":" + ss : mm + ":" + ss;
  }

  // Initial call
  initAudioPlayers();

  // ==========================================
  // Generic Infinite Slider Logic
  // ==========================================
  // ==========================================
  // Generic Infinite Slider Logic
  // ==========================================
  // ==========================================
  // Improved Infinite Slider Logic (No Clones / Dynamic Shifting)
  // ==========================================
  function initInfiniteSliders(gridSelector, autoSlideInterval = 0) {
    const grids = document.querySelectorAll(gridSelector);
    grids.forEach((grid) => {
      const wrapper = grid.closest(".book-slider-wrapper");
      if (!wrapper) return;

      const prevBtn = wrapper.querySelector(".book-slider-prev");
      const nextBtn = wrapper.querySelector(".book-slider-next");

      function getStep() {
        const card = grid.querySelector(".card, .book-sales-card");
        if (!card) return 0;
        const style = window.getComputedStyle(grid);
        const gap = parseInt(style.gap) || 0;
        return card.offsetWidth + gap;
      }

      let isTransitioning = false;

      function shiftNext() {
        if (grid.children.length === 0) return;
        const step = getStep();
        grid.style.scrollBehavior = "auto";
        grid.appendChild(grid.firstElementChild);
        grid.scrollLeft -= step;
        // force reflow
        void grid.offsetWidth;
        grid.style.scrollBehavior = "smooth";
      }

      function shiftPrev() {
        if (grid.children.length === 0) return;
        const step = getStep();
        grid.style.scrollBehavior = "auto";
        grid.insertBefore(grid.lastElementChild, grid.firstElementChild);
        grid.scrollLeft += step;
        void grid.offsetWidth;
        grid.style.scrollBehavior = "smooth";
      }

      function slideNext() {
        if (isTransitioning) return;
        isTransitioning = true;

        // If scrolled to right end, quietly shift an item
        if (Math.ceil(grid.scrollLeft + grid.clientWidth) >= grid.scrollWidth - 10) {
          shiftNext();
        }

        const step = getStep();
        grid.scrollBy({ left: step, behavior: "smooth" });
        setTimeout(() => {
          isTransitioning = false;
        }, 500);
      }

      function slidePrev() {
        if (isTransitioning) return;
        isTransitioning = true;

        // If scrolled to left end, quietly shift an item
        if (grid.scrollLeft <= 10) {
          shiftPrev();
        }

        const step = getStep();
        grid.scrollBy({ left: -step, behavior: "smooth" });
        setTimeout(() => {
          isTransitioning = false;
        }, 500);
      }

      if (nextBtn)
        nextBtn.addEventListener("click", (e) => {
          e.preventDefault();
          slideNext();
          resetTimer();
        });
      if (prevBtn)
        prevBtn.addEventListener("click", (e) => {
          e.preventDefault();
          slidePrev();
          resetTimer();
        });

      // Listen for swipe / native scroll touches hitting boundary
      grid.addEventListener(
        "scroll",
        () => {
          if (!isTransitioning) {
            if (Math.ceil(grid.scrollLeft + grid.clientWidth) >= grid.scrollWidth - 5) {
              shiftNext();
            } else if (grid.scrollLeft <= 5) {
              shiftPrev();
            }
          }
        },
        { passive: true },
      );

      let slideTimer;
      const startTimer = () => {
        if (autoSlideInterval > 0) slideTimer = setInterval(slideNext, autoSlideInterval);
      };
      const resetTimer = () => {
        if (autoSlideInterval > 0) {
          clearInterval(slideTimer);
          startTimer();
        }
      };

      if (autoSlideInterval > 0) {
        startTimer();
        grid.addEventListener("mouseenter", () => clearInterval(slideTimer));
        grid.addEventListener("mouseleave", startTimer);
        grid.addEventListener("touchstart", () => clearInterval(slideTimer), { passive: true });
        grid.addEventListener("touchend", startTimer, { passive: true });
      }

      // Init: Shift one element left initially so "Prev" button works instantly
      // without hitting an invisible wall first
      setTimeout(() => shiftPrev(), 50);
    });
  }

  // Initialize all sliders
  initInfiniteSliders(".book-sales-grid", 4000);
  initInfiniteSliders(".qa-slider-grid", 5000);

  // ==========================================
  // Prayer Time Floating Widget (AlAdhan + GPS)
  // ==========================================
  const prayerWidgetDefaults = {
    enabled: true,
    apiBase: "https://api.aladhan.com/v1",
    method: 3,
    school: 1,
    floatingPosition: "left",
    use24Hour: false,
    autoRequestLocation: true,
    highAccuracy: false,
    geoTimeoutMs: 20000,
    geoMaximumAgeMs: 300000,
    refreshIntervalMinutes: 15,
    labels: {
      nextPrayerPrefix: "\u09aa\u09b0\u09ac\u09b0\u09cd\u09a4\u09c0\u0020\u09a8\u09be\u09ae\u09be\u099c",
      locationPending: "\u09b2\u09cb\u0995\u09c7\u09b6\u09a8\u0020\u0985\u09a8\u09c1\u09ae\u09a4\u09bf\u09b0\u0020\u0985\u09aa\u09c7\u0995\u09cd\u09b7\u09be\u09df\u002e\u002e\u002e",
      locationDenied: "\u09b2\u09cb\u0995\u09c7\u09b6\u09a8\u0020\u0985\u09a8\u09c1\u09ae\u09a4\u09bf\u0020\u09a8\u09be\u0020\u09a6\u09bf\u09b2\u09c7\u0020\u098f\u099f\u09bf\u0020\u0995\u09be\u099c\u0020\u0995\u09b0\u09ac\u09c7\u0020\u09a8\u09be",
      locationUnavailable: "\u098f\u0987\u0020\u09ac\u09cd\u09b0\u09be\u0989\u099c\u09be\u09b0\u09c7\u0020\u09b2\u09cb\u0995\u09c7\u09b6\u09a8\u0020\u09b8\u09be\u09aa\u09cb\u09b0\u09cd\u099f\u0020\u09a8\u09c7\u0987",
      locationTimeout: "\u09b2\u09cb\u0995\u09c7\u09b6\u09a8\u0020\u09aa\u09c7\u09a4\u09c7\u0020\u09b8\u09ae\u09df\u0020\u09b2\u09be\u0997\u099b\u09c7\u002c\u0020\u0986\u09ac\u09be\u09b0\u0020\u099a\u09c7\u09b7\u09cd\u099f\u09be\u0020\u0995\u09b0\u09c1\u09a8",
      locationRetrying: "\u09b2\u09cb\u0995\u09c7\u09b6\u09a8\u0020\u0986\u09ac\u09be\u09b0\u0020\u09a8\u09c7\u0993\u09df\u09be\u0020\u09b9\u099a\u09cd\u099b\u09c7\u002e\u002e\u002e",
      loading: "\u09a8\u09be\u09ae\u09be\u099c\u09c7\u09b0\u0020\u09b8\u09ae\u09df\u0020\u09b2\u09cb\u09a1\u0020\u09b9\u099a\u09cd\u099b\u09c7\u002e\u002e\u002e",
      fetchError: "\u09a8\u09be\u09ae\u09be\u099c\u09c7\u09b0\u0020\u09b8\u09ae\u09df\u0020\u09b2\u09cb\u09a1\u0020\u0995\u09b0\u09be\u0020\u09af\u09be\u09df\u09a8\u09bf",
      retryHint: "\u0986\u09ac\u09be\u09b0\u0020\u099a\u09c7\u09b7\u09cd\u099f\u09be\u09b0\u0020\u099c\u09a8\u09cd\u09af\u0020\u09ab\u09cd\u09b2\u09cb\u099f\u09bf\u0982\u0020\u09ac\u09be\u099f\u09a8\u09c7\u0020\u0995\u09cd\u09b2\u09bf\u0995\u0020\u0995\u09b0\u09c1\u09a8",
      prayerTimesTitle: "\u0986\u099c\u0995\u09c7\u09b0\u0020\u09a8\u09be\u09ae\u09be\u099c\u09c7\u09b0\u0020\u09b8\u09ae\u09df",
      nextPrayerText: "\u09aa\u09b0\u09ac\u09b0\u09cd\u09a4\u09c0\u0020\u09a8\u09be\u09ae\u09be\u099c",
      sehri: "\u09b8\u09be\u09b9\u09b0\u09c0\u09b0\u0020\u09b6\u09c7\u09b7\u0020\u09b8\u09ae\u09df",
      iftar: "\u0987\u09ab\u09a4\u09be\u09b0",
      tomorrowFajr: "\u0986\u0997\u09be\u09ae\u09c0\u0995\u09be\u09b2\u09c7\u09b0\u0020\u09ab\u099c\u09b0",
    },
  };
  // Helper: convert English digits to Bengali, AM/PM to পূর্বাহ্ন/অপরাহ্ন, and month names to Bengali
  function toBanglaDigits(str) {
    const banglaDigits = ["০", "১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯"];
    const monthMap = {
      Jan: "জানুয়ারি",
      Feb: "ফেব্রুয়ারি",
      Mar: "মার্চ",
      Apr: "এপ্রিল",
      May: "মে",
      Jun: "জুন",
      Jul: "জুলাই",
      Aug: "আগস্ট",
      Sep: "সেপ্টেম্বর",
      Oct: "অক্টোবর",
      Nov: "নভেম্বর",
      Dec: "ডিসেম্বর",
    };
    return String(str)
      .replace(/\b(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\b/g, (m) => monthMap[m] || m)
      .replace(/\d/g, (d) => banglaDigits[d])
      .replace(/AM/gi, "পূর্বাহ্ন")
      .replace(/PM/gi, "অপরাহ্ন");
  }
  const prayerConfigSource = window.PRAYER_WIDGET_CONFIG || {};
  const prayerConfig = {
    ...prayerWidgetDefaults,
    ...prayerConfigSource,
    labels: {
      ...prayerWidgetDefaults.labels,
      ...(prayerConfigSource.labels || {}),
    },
  };

  const prayerElements = {
    fab: document.getElementById("prayer-floating-btn"),
    fabTitle: document.getElementById("prayer-floating-title"),
    fabCountdown: document.getElementById("prayer-floating-countdown"),
    sheet: document.getElementById("prayer-bottom-sheet"),
    sheetBackdrop: document.getElementById("prayer-sheet-backdrop"),
    sheetClose: document.getElementById("prayer-sheet-close"),
    sheetTitle: document.getElementById("prayer-sheet-title"),
    sheetLocation: document.getElementById("prayer-sheet-location"),
    sheetDate: document.getElementById("prayer-sheet-date"),
    sheetNext: document.getElementById("prayer-sheet-next"),
    list: document.getElementById("prayer-times-list"),
  };

  if (!prayerConfig.enabled) {
    if (prayerElements.fab) prayerElements.fab.style.display = "none";
    if (prayerElements.sheet) prayerElements.sheet.style.display = "none";
    if (prayerElements.sheetBackdrop) prayerElements.sheetBackdrop.style.display = "none";
  }

  if (prayerConfig.enabled && prayerElements.fab && prayerElements.sheet && prayerElements.sheetBackdrop && prayerElements.list) {
    const prayerState = {
      ready: false,
      currentCoords: null,
      todayData: null,
      tomorrowData: null,
      nextPrayer: null,
      countdownTimer: null,
      refreshTimer: null,
      geoRequestInFlight: false,
      geoWatchdogTimer: null,
    };

    // Helper: get current time relative to the target timezone (simulated local date object)
    function getCurrentTimeInTimezone(timeZone) {
      if (!timeZone) return new Date();
      try {
        const tzString = new Date().toLocaleString("en-US", { timeZone });
        return new Date(tzString);
      } catch (e) {
        return new Date();
      }
    }

    const prayerNames = {
      Fajr: "\u09ab\u099c\u09b0",
      Dhuhr: "\u09af\u09cb\u09b9\u09b0",
      Asr: "\u0986\u09b8\u09b0",
      Maghrib: "\u09ae\u09be\u0997\u09b0\u09bf\u09ac",
      Isha: "\u098f\u09b6\u09be",
    };

    const prayerRows = [
      { key: "Imsak", label: prayerConfig.labels.sehri, kind: "meta" },
      { key: "Fajr", label: prayerNames.Fajr, kind: "prayer" },
      { key: "Dhuhr", label: prayerNames.Dhuhr, kind: "prayer" },
      { key: "Asr", label: prayerNames.Asr, kind: "prayer" },
      { key: "Maghrib", label: prayerNames.Maghrib, kind: "prayer" },
      { key: "Isha", label: prayerNames.Isha, kind: "prayer" },
      { key: "Maghrib", label: prayerConfig.labels.iftar, kind: "meta" },
    ];

    const prayerLabels = { ...prayerNames };

    if (prayerElements.sheetTitle) {
      prayerElements.sheetTitle.textContent = prayerConfig.labels.prayerTimesTitle;
    }

    function setFabMessage(message, isDisabled) {
      if (prayerElements.fabTitle) {
        prayerElements.fabTitle.textContent = prayerConfig.labels.nextPrayerPrefix;
      }
      if (prayerElements.fabCountdown) {
        prayerElements.fabCountdown.textContent = message;
      }
      if (isDisabled) {
        prayerElements.fab.classList.add("is-disabled");
        prayerElements.fab.setAttribute("disabled", "disabled");
        prayerElements.fab.setAttribute("aria-disabled", "true");
      } else {
        prayerElements.fab.classList.remove("is-disabled");
        prayerElements.fab.removeAttribute("disabled");
        prayerElements.fab.setAttribute("aria-disabled", "false");
      }
    }

    function renderStatusRow(message) {
      prayerElements.list.innerHTML = "";
      const li = document.createElement("li");
      li.className = "prayer-time-item is-meta";

      const name = document.createElement("span");
      name.className = "prayer-name";
      name.textContent = message;

      const time = document.createElement("span");
      time.className = "prayer-time";
      time.textContent = "";

      li.appendChild(name);
      li.appendChild(time);
      prayerElements.list.appendChild(li);
    }

    function openPrayerSheet() {
      if (prayerElements.fab.hasAttribute("disabled")) {
        return;
      }
      prayerElements.sheet.classList.add("open");
      prayerElements.sheetBackdrop.hidden = false;
      prayerElements.sheet.setAttribute("aria-hidden", "false");
      prayerElements.fab.setAttribute("aria-expanded", "true");
      document.body.style.overflow = "hidden";
    }

    function closePrayerSheet() {
      prayerElements.sheet.classList.remove("open");
      prayerElements.sheetBackdrop.hidden = true;
      prayerElements.sheet.setAttribute("aria-hidden", "true");
      prayerElements.fab.setAttribute("aria-expanded", "false");
      document.body.style.overflow = "";
    }

    function formatDisplayTime(timeStr) {
      if (!timeStr) return "--:--";
      const cleanValue = String(timeStr).split(" ")[0];
      const parts = cleanValue.split(":");
      if (parts.length < 2) return cleanValue;
      const dateValue = new Date();
      dateValue.setHours(Number(parts[0]), Number(parts[1]), 0, 0);
      const formatted = new Intl.DateTimeFormat("bn-BD", {
        hour: "numeric",
        minute: "2-digit",
        hour12: !prayerConfig.use24Hour,
      }).format(dateValue);
      return toBanglaDigits(formatted);
    }

    function parseTimeToDate(baseDate, timeStr) {
      const cleanValue = String(timeStr || "")
        .split(" ")[0]
        .trim();
      const parts = cleanValue.split(":");
      if (parts.length < 2) return null;
      const hour = Number(parts[0]);
      const minute = Number(parts[1]);
      if (Number.isNaN(hour) || Number.isNaN(minute)) return null;
      const dt = new Date(baseDate);
      dt.setHours(hour, minute, 0, 0);
      return dt;
    }

    function secondsToClock(totalSeconds) {
      const sec = Math.max(0, totalSeconds);
      const hh = Math.floor(sec / 3600);
      const mm = Math.floor((sec % 3600) / 60);
      const ss = sec % 60;
      return [hh, mm, ss].map((v) => String(v).padStart(2, "0")).join(":");
    }

    function computeNextPrayer(now, todayTimings, tomorrowTimings) {
      const today = new Date(now);
      const tomorrow = new Date(now);
      tomorrow.setDate(tomorrow.getDate() + 1);

      const prayerOrder = ["Fajr", "Dhuhr", "Asr", "Maghrib", "Isha"];
      const todaySlots = prayerOrder
        .map((key) => ({
          key,
          label: prayerLabels[key],
          date: parseTimeToDate(today, todayTimings[key]),
        }))
        .filter((item) => item.date instanceof Date);

      const upcomingToday = todaySlots.find((item) => item.date > now);
      if (upcomingToday) {
        return {
          key: upcomingToday.key,
          label: upcomingToday.label,
          target: upcomingToday.date,
          tomorrow: false,
        };
      }

      const nextFajr = parseTimeToDate(tomorrow, tomorrowTimings.Fajr);
      return {
        key: "Fajr",
        label: prayerConfig.labels.tomorrowFajr,
        target: nextFajr,
        tomorrow: true,
      };
    }

    function renderPrayerRows() {
      if (!prayerState.todayData || !prayerState.todayData.timings) return;

      const tz = prayerState.todayData.meta ? prayerState.todayData.meta.timezone : null;
      const now = getCurrentTimeInTimezone(tz);
      prayerElements.list.innerHTML = "";

      prayerRows.forEach((row) => {
        const timeValue = prayerState.todayData.timings[row.key] || "--:--";
        const rowDate = parseTimeToDate(now, timeValue);
        const li = document.createElement("li");
        li.className = "prayer-time-item";

        if (row.kind === "meta") {
          li.classList.add("is-meta");
        } else if (rowDate && rowDate < now) {
          li.classList.add("is-passed");
        }

        if (row.kind === "prayer" && prayerState.nextPrayer && !prayerState.nextPrayer.tomorrow && prayerState.nextPrayer.key === row.key) {
          li.classList.add("is-next");
        }

        const name = document.createElement("span");
        name.className = "prayer-name";
        name.textContent = row.label;

        const time = document.createElement("span");
        time.className = "prayer-time";
        time.textContent = formatDisplayTime(timeValue);

        li.appendChild(name);
        li.appendChild(time);
        prayerElements.list.appendChild(li);
      });
    }

    function updateCountdown() {
      if (!prayerState.nextPrayer || !prayerState.nextPrayer.target) return;

      const tz = prayerState.todayData && prayerState.todayData.meta ? prayerState.todayData.meta.timezone : null;
      const now = getCurrentTimeInTimezone(tz);
      const secondsLeft = Math.floor((prayerState.nextPrayer.target - now) / 1000);

      if (secondsLeft <= 0) {
        refreshPrayerData();
        return;
      }

      const label = prayerState.nextPrayer.label;

      // format the target time (e.g 8:30 PM) instead of the remaining countdown
      const formattedTargetTime = toBanglaDigits(
        new Intl.DateTimeFormat("bn-BD", {
          hour: "numeric",
          minute: "2-digit",
          hour12: !prayerConfig.use24Hour,
        }).format(prayerState.nextPrayer.target),
      );

      prayerElements.fabCountdown.textContent = `${label} \u2022 ${formattedTargetTime}`;
      prayerElements.sheetNext.textContent = `${prayerConfig.labels.nextPrayerText}: ${label} (${formattedTargetTime})`;
    }

    async function fetchTimingsByDate(lat, lon, targetDate) {
      const unixTs = Math.floor(targetDate.getTime() / 1000);
      const query = new URLSearchParams({
        latitude: String(lat),
        longitude: String(lon),
        method: String(prayerConfig.method),
        school: String(prayerConfig.school),
      });
      const url = `${prayerConfig.apiBase}/timings/${unixTs}?${query.toString()}`;
      const res = await fetch(url);
      if (!res.ok) {
        throw new Error(`Prayer API HTTP ${res.status}`);
      }
      const payload = await res.json();
      if (Number(payload.code) !== 200 || !payload.data || !payload.data.timings) {
        throw new Error("Prayer API invalid payload");
      }
      return payload.data;
    }

    async function refreshPrayerData() {
      if (!prayerState.currentCoords) return;
      const { latitude, longitude } = prayerState.currentCoords;
      const now = new Date();
      const tomorrow = new Date(now);
      tomorrow.setDate(tomorrow.getDate() + 1);

      try {
        prayerState.ready = false;
        setFabMessage(prayerConfig.labels.loading, false);
        prayerElements.sheetNext.textContent = prayerConfig.labels.loading;
        renderStatusRow(prayerConfig.labels.loading);
        const [todayData, tomorrowData] = await Promise.all([fetchTimingsByDate(latitude, longitude, now), fetchTimingsByDate(latitude, longitude, tomorrow)]);

        prayerState.todayData = todayData;
        prayerState.tomorrowData = tomorrowData;

        const tz = todayData.meta && todayData.meta.timezone ? todayData.meta.timezone : null;
        const localNow = getCurrentTimeInTimezone(tz);

        prayerState.nextPrayer = computeNextPrayer(localNow, todayData.timings, tomorrowData.timings);
        prayerState.ready = true;

        let readableDate = "";
        if (todayData.date && todayData.date.hijri && todayData.date.gregorian) {
          const hDay = todayData.date.hijri.day;
          
          const hijriMonthsEn = {
            1: "Muharram", 2: "Safar", 3: "Rabi' al-awwal", 4: "Rabi' al-thani",
            5: "Jumada al-ula", 6: "Jumada al-akhira", 7: "Rajab", 8: "Sha'ban",
            9: "Ramadan", 10: "Shawwal", 11: "Dhu al-Qi'dah", 12: "Dhu al-Hijjah"
          };
          const mNum = parseInt(todayData.date.hijri.month.number);
          const hMonth = hijriMonthsEn[mNum] || todayData.date.hijri.month.en;
          const hYear = todayData.date.hijri.year;

          const gDay = todayData.date.gregorian.day;
          const gMonth = todayData.date.gregorian.month.en;
          const gYear = todayData.date.gregorian.year;

          readableDate = `${hDay} ${hMonth} ${hYear} Hijri  •  ${gDay} ${gMonth} ${gYear}`;
        } else if (todayData.date && todayData.date.readable) {
          readableDate = todayData.date.readable;
        } else {
          readableDate = new Date().toLocaleDateString('en-US', { day: 'numeric', month: 'long', year: 'numeric' });
        }

        prayerElements.sheetDate.textContent = readableDate;

        const hijriHeaderElement = document.getElementById("hijri-date");
        if (hijriHeaderElement) {
          hijriHeaderElement.textContent = readableDate;
        }

        if (prayerElements.sheetLocation.textContent === "GPS" || prayerElements.sheetLocation.textContent.startsWith("GPS:")) {
          try {
            const geoRes = await fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${latitude}&longitude=${longitude}&localityLanguage=bn`);
            if (geoRes.ok) {
              const geoData = await geoRes.json();
              // Extract district name (adminLevel 5 = জেলা) from localityInfo
              let districtName = "";
              if (geoData.localityInfo && geoData.localityInfo.administrative) {
                const district = geoData.localityInfo.administrative.find((a) => a.adminLevel === 5);
                if (district && district.name) {
                  // Remove "জেলা" suffix if present (e.g., "জয়পুরহাট জেলা" → "জয়পুরহাট")
                  districtName = district.name.replace(/\s*জেলা\s*$/, "").trim();
                }
              }
              prayerElements.sheetLocation.textContent = districtName || geoData.city || geoData.locality || geoData.principalSubdivision || `GPS: ${latitude.toFixed(4)}, ${longitude.toFixed(4)}`;
            } else {
              prayerElements.sheetLocation.textContent = `GPS: ${latitude.toFixed(4)}, ${longitude.toFixed(4)}`;
            }
          } catch (e) {
            prayerElements.sheetLocation.textContent = `GPS: ${latitude.toFixed(4)}, ${longitude.toFixed(4)}`;
          }
        }

        renderPrayerRows();

        if (prayerState.countdownTimer) {
          clearInterval(prayerState.countdownTimer);
        }
        prayerState.countdownTimer = setInterval(updateCountdown, 1000);
        updateCountdown();
      } catch (err) {
        setFabMessage(prayerConfig.labels.fetchError, false);
        prayerElements.sheetNext.textContent = prayerConfig.labels.fetchError;
        if (!prayerElements.sheetLocation.textContent) {
          prayerElements.sheetLocation.textContent = prayerConfig.labels.fetchError;
        }
        renderStatusRow(prayerConfig.labels.retryHint);
        prayerState.ready = false;
      }
    }

    function onLocationSuccess(position) {
      if (prayerState.locationResolved) return; // already resolved by IP geo
      prayerState.locationResolved = true;
      prayerState.currentCoords = {
        latitude: position.coords.latitude,
        longitude: position.coords.longitude,
      };
      prayerElements.sheetLocation.textContent = "GPS";
      refreshPrayerData();
    }

    function onLocationError() {
      // GPS failed — IP geo is already running in parallel, nothing to do here
    }

    // Helper: fetch with timeout to prevent hanging
    function fetchWithTimeout(url, timeoutMs = 5000) {
      const controller = new AbortController();
      const timer = setTimeout(() => controller.abort(), timeoutMs);
      return fetch(url, { signal: controller.signal }).finally(() => clearTimeout(timer));
    }

    async function fetchIpLocation() {
      // Provider 1: freeipapi (Highly reliable, rarely blocked by adblockers)
      try {
        const res = await fetchWithTimeout("https://freeipapi.com/api/json", 5000);
        if (res.ok) {
          const data = await res.json();
          if (data.latitude && data.longitude) return data;
        }
      } catch (_) {}

      // Provider 2: ipwho.is
      try {
        const res = await fetchWithTimeout("https://ipwho.is/", 5000);
        if (res.ok) {
          const data = await res.json();
          if (data.success && data.latitude && data.longitude) {
            return { latitude: data.latitude, longitude: data.longitude };
          }
        }
      } catch (_) {}

      // Provider 3: ipinfo.io
      try {
        const res = await fetchWithTimeout("https://ipinfo.io/json", 5000);
        if (res.ok) {
          const data = await res.json();
          if (data.loc) {
            const parts = data.loc.split(",");
            return {
              latitude: parseFloat(parts[0]),
              longitude: parseFloat(parts[1]),
            };
          }
        }
      } catch (_) {}

      // Provider 4: ipapi.co (often rate-limited but good last resort)
      const res = await fetchWithTimeout("https://ipapi.co/json/", 5000);
      if (!res.ok) throw new Error("All IP geo APIs failed");
      const data = await res.json();
      if (!data.latitude || !data.longitude) throw new Error("No coords");
      return data;
    }

    async function requestLocationPermission() {
      if (!window.isSecureContext || !navigator.geolocation) {
        await useFallbackLocation();
        return;
      }

      prayerState.locationResolved = false;
      setFabMessage(prayerConfig.labels.loading, false);
      renderStatusRow(prayerConfig.labels.loading);

      // Start IP geo immediately (fast) — show prayer times quickly
      fetchIpLocation()
        .then((data) => {
          if (!prayerState.locationResolved) {
            prayerState.locationResolved = true;
            prayerState.currentCoords = {
              latitude: data.latitude,
              longitude: data.longitude,
            };
            prayerElements.sheetLocation.textContent = "GPS";
            refreshPrayerData();
          }
        })
        .catch(() => {
          // IP failed — if GPS hasn't resolved either, use hardcoded fallback
          if (!prayerState.locationResolved) {
            useFallbackLocation();
          }
        });

      // Start GPS in parallel with longer timeout (15s for mobile)
      navigator.geolocation.getCurrentPosition(
        (pos) => {
          // GPS succeeded
          const isLowAccuracy = pos.coords.accuracy > 5000;

          // If the browser GPS is low accuracy (e.g. desktop PC falling back to Dhaka config),
          // completely ignore it because our IP geocoding is more accurate for Bangladesh.
          // An instant cached low-accuracy GPS result will no longer block IP fetching.
          if (isLowAccuracy) {
            return;
          }

          // Otherwise, always upgrade to precise GPS coordinates
          prayerState.locationResolved = true;
          prayerState.currentCoords = {
            latitude: pos.coords.latitude,
            longitude: pos.coords.longitude,
          };
          prayerElements.sheetLocation.textContent = "GPS";
          refreshPrayerData();
        },
        () => {
          // GPS failed — IP geo or fallback already handling it
        },
        { enableHighAccuracy: true, timeout: 15000, maximumAge: 300000 },
      );
    }

    async function useFallbackLocation() {
      try {
        const data = await fetchIpLocation();
        prayerState.currentCoords = {
          latitude: data.latitude,
          longitude: data.longitude,
        };
        // Set as "GPS" so refreshPrayerData will use reverse geocoding for Bengali name
        prayerElements.sheetLocation.textContent = "GPS";
        refreshPrayerData();
      } catch (_) {
        const coords = prayerConfig.fallbackCoords || {
          latitude: 23.8103,
          longitude: 90.4125,
        };
        prayerState.currentCoords = coords;
        prayerElements.sheetLocation.textContent = prayerConfig.fallbackCity || "ঢাকা";
        refreshPrayerData();
      }
    }

    // Position controls for theme/WP settings
    if (prayerConfig.floatingPosition === "right") {
      prayerElements.fab.classList.add("is-right");
      prayerElements.sheet.classList.add("is-right");
    }

    prayerElements.fab.addEventListener("click", function () {
      openPrayerSheet();
      if (prayerElements.fab.hasAttribute("disabled")) {
        return;
      }

      if (!prayerState.currentCoords) {
        requestLocationPermission(false);
        return;
      }

      if (!prayerState.ready) {
        refreshPrayerData();
      }
    });

    if (prayerElements.sheetClose) {
      prayerElements.sheetClose.addEventListener("click", closePrayerSheet);
    }

    prayerElements.sheetBackdrop.addEventListener("click", closePrayerSheet);

    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && prayerElements.sheet.classList.contains("open")) {
        closePrayerSheet();
      }
    });

    if (prayerConfig.autoRequestLocation) {
      requestLocationPermission(false);
    }

    if (prayerState.refreshTimer) {
      clearInterval(prayerState.refreshTimer);
    }
    prayerState.refreshTimer = setInterval(refreshPrayerData, Math.max(5, Number(prayerConfig.refreshIntervalMinutes) || 15) * 60 * 1000);
  }

  /* ==========================================
     SHOPPING CART LOGIC
     ========================================== */
  const cartState = {
    items: JSON.parse(localStorage.getItem("hd_cart")) || [],
  };

  const cartModalOverlay = document.getElementById("cartModalOverlay");
  const cartModalCloseBtn = document.getElementById("cartModalCloseBtn");
  const headerCartBtn = document.getElementById("headerCartBtn");
  const cartItemsContainer = document.getElementById("cartItemsContainer");
  const cartTotalPriceEl = document.getElementById("cartTotalPrice");
  const cartCountBadge = document.getElementById("cartCountBadge");
  const toastContainer = document.getElementById("toast-container");

  if (headerCartBtn && cartModalOverlay) {
    headerCartBtn.addEventListener("click", () => {
      cartModalOverlay.classList.add("active");
    });

    cartModalCloseBtn.addEventListener("click", () => {
      cartModalOverlay.classList.remove("active");
    });

    // Close when clicking outside of modal
    cartModalOverlay.addEventListener("click", (e) => {
      if (e.target === cartModalOverlay) {
        cartModalOverlay.classList.remove("active");
      }
    });
  }

  function showToast(message) {
    if (!toastContainer) return;
    const toast = document.createElement("div");
    toast.className = "toast-message";
    toast.innerHTML = `<span class="material-symbols-outlined">check_circle</span> ${message}`;
    toastContainer.appendChild(toast);

    setTimeout(() => {
      if (toast.parentNode) {
        toast.parentNode.removeChild(toast);
      }
    }, 3000);
  }

  function updateCartUI() {
    // Save to localStorage
    localStorage.setItem("hd_cart", JSON.stringify(cartState.items));
    window.dispatchEvent(new Event("hd_cart_updated"));

    // Calculate total quantity for badge
    const totalItems = cartState.items.reduce((sum, item) => sum + (item.quantity || 1), 0);

    if (cartCountBadge) {
      cartCountBadge.textContent = toBanglaDigits(totalItems);
    }

    if (cartState.items.length === 0) {
      if (cartItemsContainer) cartItemsContainer.innerHTML = '<div class="empty-cart-msg">কার্টে কোন বই নেই।</div>';
      if (cartTotalPriceEl) cartTotalPriceEl.textContent = "৳ ০";
      return;
    }

    if (cartItemsContainer) {
      cartItemsContainer.innerHTML = "";
      let total = 0;

      cartState.items.forEach((item, index) => {
        const qty = item.quantity || 1;
        total += item.price * qty;

        const itemEl = document.createElement("div");
        itemEl.className = "cart-item";
        itemEl.innerHTML = `
          <img src="${item.img}" alt="${item.title}">
          <div class="cart-item-details">
            <h4 class="cart-item-title">${item.title}</h4>
            <div class="cart-item-price">৳ ${toBanglaDigits(item.price)}</div>
          </div>
          <div class="cart-item-actions">
            <div class="cart-qty-controls">
              <button class="cart-qty-btn minus-btn" data-index="${index}">-</button>
              <span class="cart-qty-text">${toBanglaDigits(qty)}</span>
              <button class="cart-qty-btn plus-btn" data-index="${index}">+</button>
            </div>
            <button class="cart-item-remove" data-index="${index}" title="রিমুভ করুন">
              <span class="material-symbols-outlined">delete</span>
            </button>
          </div>
        `;
        cartItemsContainer.appendChild(itemEl);
      });

      // Add listeners for plus buttons
      const plusBtns = cartItemsContainer.querySelectorAll(".plus-btn");
      plusBtns.forEach((btn) => {
        btn.addEventListener("click", (e) => {
          const idx = e.currentTarget.getAttribute("data-index");
          if (!cartState.items[idx].quantity) cartState.items[idx].quantity = 1;
          cartState.items[idx].quantity++;
          updateCartUI();
        });
      });

      // Add listeners for minus buttons
      const minusBtns = cartItemsContainer.querySelectorAll(".minus-btn");
      minusBtns.forEach((btn) => {
        btn.addEventListener("click", (e) => {
          const idx = e.currentTarget.getAttribute("data-index");
          if (!cartState.items[idx].quantity) cartState.items[idx].quantity = 1;
          if (cartState.items[idx].quantity > 1) {
            cartState.items[idx].quantity--;
          } else {
            cartState.items.splice(idx, 1); // remove if quantity goes below 1
          }
          updateCartUI();
        });
      });

      // Add remove listeners
      const removeBtns = cartItemsContainer.querySelectorAll(".cart-item-remove");
      removeBtns.forEach((btn) => {
        btn.addEventListener("click", (e) => {
          const idx = e.currentTarget.getAttribute("data-index");
          cartState.items.splice(idx, 1);
          updateCartUI();
        });
      });

      if (cartTotalPriceEl) {
        cartTotalPriceEl.textContent = "৳ " + toBanglaDigits(total);
      }
    }
  }

  // Initialize cart on page load
  updateCartUI();

  // Bind order buttons
  const orderButtons = document.querySelectorAll(".book-sales-order-btn");
  orderButtons.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();

      const card = btn.closest(".book-sales-card, .book-archive-card");
      if (!card) return;

      const title = card.querySelector("h3") ? card.querySelector("h3").innerText : "নতুন বই";
      const img = card.querySelector("img") ? card.querySelector("img").src : "";
      const priceStr = card.querySelector(".book-sales-price") ? card.querySelector(".book-sales-price").innerText : "0";

      // Extract numbers from bengali string
      let numericPriceStr = priceStr.replace(/[^০-৯0-9]/g, "");
      // Convert mapping
      const engDigits = numericPriceStr.replace(/[০-৯]/g, (d) => "০১২৩৪৫৬৭৮৯".indexOf(d));
      const price = parseInt(engDigits, 10) || 0;

      // Check if item already exists in cart
      const existingItemIndex = cartState.items.findIndex((item) => item.title === title);

      if (existingItemIndex > -1) {
        if (!cartState.items[existingItemIndex].quantity) {
          cartState.items[existingItemIndex].quantity = 1;
        }
        cartState.items[existingItemIndex].quantity++;
      } else {
        cartState.items.push({
          title,
          img,
          price,
          quantity: 1,
        });
      }

      updateCartUI();
      showToast(`${title} কার্টে যোগ করা হয়েছে!`);
    });
  });

  // ==========================================
  // Archive View Toggle (Grid / List)
  // ==========================================
  const archiveViewToggles = document.querySelectorAll(".archive-view-toggle");

  archiveViewToggles.forEach((toggleGroup) => {
    const toggleButtons = toggleGroup.querySelectorAll(".view-toggle-btn[data-view]");
    if (!toggleButtons.length) return;

    const targetSelector = toggleGroup.getAttribute("data-view-target");
    if (!targetSelector) return;

    const targetGrid = document.querySelector(targetSelector);
    if (!targetGrid) return;

    toggleButtons.forEach((btn) => {
      btn.addEventListener("click", function () {
        const view = this.getAttribute("data-view");

        toggleButtons.forEach((item) => item.classList.remove("active"));
        this.classList.add("active");

        if (view === "list") {
          targetGrid.classList.add("list-view");
        } else {
          targetGrid.classList.remove("list-view");
        }
      });
    });
  });

  // Single Book Quantity Stepper & Add to Cart Fix
  const qtyInput = document.getElementById("sbQtyInput");
  const qtyMinus = document.getElementById("sbQtyMinus");
  const qtyPlus = document.getElementById("sbQtyPlus");
  const sbAddToCartBtn = document.querySelector(".sb-add-to-cart-btn");

  if (qtyInput && qtyMinus && qtyPlus) {
    qtyMinus.addEventListener("click", () => {
      let val = parseInt(qtyInput.value) || 1;
      if (val > 1) qtyInput.value = val - 1;
    });
    qtyPlus.addEventListener("click", () => {
      let val = parseInt(qtyInput.value) || 1;
      if (val < 20) qtyInput.value = val + 1;
    });
  }

  if (sbAddToCartBtn) {
    sbAddToCartBtn.addEventListener("click", function () {
      const title = this.getAttribute("data-book-title");
      const price = parseInt(this.getAttribute("data-book-price")) || 0;
      const img = document.getElementById("sbMainImg") ? document.getElementById("sbMainImg").src : "";
      const qty = qtyInput ? parseInt(qtyInput.value) || 1 : 1;

      const existingItemIndex = cartState.items.findIndex((item) => item.title === title);

      if (existingItemIndex > -1) {
        if (!cartState.items[existingItemIndex].quantity) {
          cartState.items[existingItemIndex].quantity = 0;
        }
        cartState.items[existingItemIndex].quantity += qty;
      } else {
        cartState.items.push({
          title,
          img,
          price,
          quantity: qty,
        });
      }

      updateCartUI();
      showToast(`${title} কার্টে যোগ করা হয়েছে!`);
    });
  }

  // ==========================================
  // Year Filter Dropdown Toggle
  // ==========================================
  const yearFilterBtn = document.getElementById("yearFilterBtn");
  const yearDropdown = document.querySelector(".year-dropdown");

  if (yearFilterBtn && yearDropdown) {
    yearFilterBtn.addEventListener("click", function (e) {
      e.stopPropagation();
      yearDropdown.classList.toggle("active");
    });

    // Close dropdown when clicking outside
    document.addEventListener("click", function (e) {
      if (!yearDropdown.contains(e.target)) {
        yearDropdown.classList.remove("active");
      }
    });

    // Handle year selection (for demo/UI purposes)
    const yearLinks = document.querySelectorAll(".year-dropdown-content a");
    const selectedYearText = yearDropdown.querySelector(".selected-year");

    yearLinks.forEach((link) => {
      link.addEventListener("click", function (e) {
        e.preventDefault();
        yearLinks.forEach((l) => l.classList.remove("active"));
        this.classList.add("active");

        const year = this.textContent.split("(")[0].trim();
        if (selectedYearText) {
          selectedYearText.textContent = year === "সব সাল" ? "সাল অনুযায়ী" : year;
        }
        yearDropdown.classList.remove("active");

        // Here you would typically trigger your filter logic
        console.log("Filtering by year:", year);
      });
    });
  }

  console.log("Website loaded successfully.");

  // ==========================================
  // Audio Archive AJAX Filtering
  // ==========================================
  const audioAjax = {
    $grid: document.getElementById('archiveAudioGrid'),
    $pagination: document.getElementById('audioPagination'),
    $loader: document.getElementById('audioLoader'),
    $count: document.querySelector('.archive-count-badge'),
    
    // Selects
    $speaker: document.getElementById('audioSpeakerFilter'),
    $topic: document.getElementById('audioTopicFilter'),
    $sort: document.getElementById('audioSortSelect'),
    $searchInput: document.getElementById('audioSearchInput'),
    $searchForm: document.getElementById('audioSearchForm'),

    init: function() {
      if (!this.$grid) return;

      const self = this;
      const triggers = [this.$speaker, this.$topic, this.$sort];
      
      triggers.forEach(el => {
        if (el) el.addEventListener('change', () => self.filter(1));
      });

      if (this.$searchForm) {
        this.$searchForm.addEventListener('submit', (e) => {
          e.preventDefault();
          self.filter(1);
        });
      }

      // Pagination click handling
      document.addEventListener('click', (e) => {
        const link = e.target.closest('#audioPagination a');
        if (link) {
          e.preventDefault();
          const url = new URL(link.href);
          const paged = url.searchParams.get('paged') || 1;
          self.filter(paged);
        }
      });
    },

    filter: function(paged = 1) {
      const self = this;
      
      // Values are now IDs (integers), so no encoding issue
      const data = new URLSearchParams({
        action: 'filter_audio',
        speaker: this.$speaker ? this.$speaker.value : '',
        topic: this.$topic ? this.$topic.value : '',
        orderby: this.$sort ? this.$sort.value : 'newest',
        search: this.$searchInput ? this.$searchInput.value : '',
        paged: paged
      });

      // Show loader
      if (this.$loader) {
        this.$loader.style.display = 'flex';
        this.$grid.style.opacity = '0.5';
      }

      fetch(window.location.origin + '/wp-admin/admin-ajax.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: data.toString()
      })
      .then(res => res.json())
      .then(res => {
        if (res.success) {
          // Update Grid
          const tempDiv = document.createElement('div');
          tempDiv.innerHTML = res.data.html;

          const paginationData = tempDiv.querySelector('.ajax-pagination-data');
          const countData = tempDiv.querySelector('.ajax-count-data');

          // Always update pagination and count
          if (self.$pagination) {
            self.$pagination.innerHTML = paginationData ? paginationData.innerHTML : '';
          }
          
          if (self.$count && countData) {
              const countText = countData.textContent;
              const icon = self.$count.querySelector('.material-symbols-outlined').outerHTML;
              self.$count.innerHTML = icon + ' মোট ' + countText + 'টি অডিও';
          }

          if (paginationData) paginationData.remove();
          if (countData) countData.remove();

          self.$grid.innerHTML = tempDiv.innerHTML;

          // Re-init players and other triggers
          if (typeof window.initAudioPlayers === 'function') {
            window.initAudioPlayers();
          }
          
          // Trigger DOMContentLoaded events for YouTube
          const event = new Event('DOMContentLoaded');
          document.dispatchEvent(event);

          // Scroll to top of grid
          window.scrollTo({
            top: self.$grid.getBoundingClientRect().top + window.pageYOffset - 100,
            behavior: 'smooth'
          });
        }
      })
      .catch(err => console.error('AJAX Error:', err))
      .finally(() => {
        if (self.$loader) {
          self.$loader.style.display = 'none';
          self.$grid.style.opacity = '1';
        }
      });
    }
  };

  audioAjax.init();
});

