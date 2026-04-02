// ==========================================
// Page-Specific Inline Scripts (Auto-Merged)
// ==========================================
document.addEventListener("DOMContentLoaded", function () {
  // 1. Toast Notification System
  window.showToast = function (message) {
    const toastContainer = document.getElementById("toast-container");
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
  };

  // 1.1 Show toast from WooCommerce messages after page reload
  const wcMessage = document.querySelector('.woocommerce-message');
  if (wcMessage) {
    const messageText = wcMessage.innerText.trim();
    // Clean up the message (remove button text like "View Cart")
    const cleanMessage = messageText.replace(/View Cart|→/gi, '').trim();
    setTimeout(() => {
        if (typeof window.showToast === 'function') {
            window.showToast(cleanMessage);
            wcMessage.style.display = 'none'; // Hide the default WC notice
        }
    }, 500);
  }

  // 1.2 Cart Drawer (Modal) Logic
  const cartModal = document.getElementById("cartModalOverlay");
  const cartOpenBtn = document.getElementById("headerCartBtn");
  const cartCloseBtn = document.getElementById("cartModalCloseBtn");

  window.openCartDrawer = function() {
    if (cartModal) {
      cartModal.classList.add("active");
      document.body.style.overflow = "hidden";
    }
  };

  window.closeCartDrawer = function() {
    if (cartModal) {
      cartModal.classList.remove("active");
      document.body.style.overflow = "";
    }
  };

  if (cartOpenBtn) {
    cartOpenBtn.addEventListener("click", function(e) {
      // If it's the cart page, let it navigate normally, otherwise open drawer
      if (window.location.pathname.includes('/cart/')) return;
      
      e.preventDefault();
      window.openCartDrawer();
    });
  }

  // 1.4 AJAX Auto-update cart on quantity change and remove
  if (window.jQuery) {
    const $ = window.jQuery;
    let cartUpdateTimer;

    function showHclLoader() {
        if (!document.getElementById('hcl-loader-style')) {
            const style = document.createElement('style');
            style.id = 'hcl-loader-style';
            style.innerHTML = `
            #hcl-overlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(255,255,255,0.7); z-index: 999999; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(3px); }
            .hcl-spinner { width: 60px; height: 60px; border: 5px solid rgba(6, 95, 70, 0.15); border-top-color: #065F46; border-radius: 50%; animation: hcl_spin 0.8s linear infinite; box-shadow: 0 4px 20px rgba(6, 95, 70, 0.15); }
            @keyframes hcl_spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
            `;
            document.head.appendChild(style);
        }

        if (!document.getElementById('hcl-overlay')) {
            const overlay = document.createElement('div');
            overlay.id = 'hcl-overlay';
            overlay.innerHTML = '<div class="hcl-spinner"></div>';
            document.body.appendChild(overlay);
        }
    }

    function hideHclLoader() {
        const ov = document.getElementById('hcl-overlay');
        if (ov) ov.remove();
    }
    
    // Qty change update
    $(document).on('change', '.woocommerce-cart-form input.qty', function() {
        if (cartUpdateTimer) clearTimeout(cartUpdateTimer);
        
        const $form = $(this).closest('form');
        const $container = $('.archive-section');
        
        cartUpdateTimer = setTimeout(function() {
            showHclLoader();
            
            const formData = $form.serialize() + '&update_cart=Update%20Cart';
            
            $.ajax({
                type: 'POST',
                url: window.location.href,
                data: formData,
                success: function(response) {
                    const $newContent = $(response).find('.archive-section').html();
                    if ($newContent) {
                        $container.html($newContent);
                    } else {
                        window.location.reload();
                    }
                    
                    hideHclLoader();
                    $(document.body).trigger('updated_cart_totals');
                    $(document.body).trigger('wc_fragment_refresh');
                },
                error: function() {
                    hideHclLoader();
                    window.location.reload();
                }
            });
        }, 100);
    });

    // Remove item update
    $(document).on('click', '.cart-remove-btn', function(e) {
        e.preventDefault();
        const removeUrl = $(this).attr('href');
        const $container = $('.archive-section');
        
        showHclLoader();
        
        $.ajax({
            url: removeUrl,
            type: 'GET',
            success: function(response) {
                const $newContent = $(response).find('.archive-section').html();
                if ($newContent) {
                    $container.html($newContent);
                } else {
                    window.location.reload();
                }
                
                hideHclLoader();
                $(document.body).trigger('updated_cart_totals');
                $(document.body).trigger('wc_fragment_refresh');
            },
            error: function() {
                hideHclLoader();
                window.location.reload();
            }
        });
    });

    // Handle clicks on custom plus/minus buttons
    $(document).on('click', '.sb-qty-btn', function() {
        const $container = $(this).closest('.sb-quantity, .sb-qty-stepper');
        const $input = $container.find('input.qty');
        if ($input.length) {
            $input.trigger('change');
        }
    });
  }

  if (cartCloseBtn) {
    cartCloseBtn.addEventListener("click", window.closeCartDrawer);
  }

  if (cartModal) {
    cartModal.addEventListener("click", function(e) {
      if (e.target === cartModal) window.closeCartDrawer();
    });
  }

  // 1.3 WooCommerce AJAX Success listeners
  if (window.jQuery) {
    const $ = window.jQuery;
    window.jQuery(document.body).on('added_to_cart', function(event, fragments, cart_hash, $button) {
      // 1. Show Toast
      let title = 'Book';
      if ($button && $button.length > 0) {
        const card = $button[0].closest('.book-sales-card, .book-archive-card, .product');
        if (card) {
          const titleEl = card.querySelector('h3, .woocommerce-loop-product__title, .product_title');
          if (titleEl) title = titleEl.innerText;
        }
      }
      window.showToast(`${title} added to cart!`);
      
      // 2. Refresh fragments (handled by WC automatically, but we ensure updates)
      if (fragments) {
        // Fragments are already updated by WC jQuery handler if we use standard IDs
      }

      // 3. Automatically open the side drawer
      setTimeout(window.openCartDrawer, 500);
    });

    // Handle removal from side cart via AJAX
    $(document).on('click', '.side-cart-remove', function(e) {
      e.preventDefault();
      const $this = $(this);
      const cart_item_key = $this.data('cart_item_key');
      
      $this.closest('.side-cart-item').css('opacity', '0.5');

      $.ajax({
        type: 'POST',
        url: wc_add_to_cart_params.ajax_url,
        data: {
          action: 'woocommerce_remove_from_cart',
          cart_item_key: cart_item_key
        },
        success: function(response) {
          if (response && response.fragments) {
            $(document.body).trigger('removed_from_cart', [response.fragments, response.cart_hash, $this]);
          }
        }
      });
    });

    $(document.body).on('removed_from_cart', function(e, fragments) {
        // WC updates fragments automatically
    });
  }

  // 1.5 window.PRAYER_WIDGET_CONFIG (moved from inline)
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
        nextPrayerPrefix: "Next Prayer",
        locationPending: "Waiting for location permission...",
        locationDenied: "This will not work without location permission",
        locationUnavailable: "Location not supported in this browser",
        loading: "Loading data...",
        fetchError: "Could not load prayer times",
        prayerTimesTitle: "Today's Prayer Times",
        nextPrayerText: "Next Prayer",
        sehri: "Sehri Ends",
        iftar: "Iftar",
        tomorrowFajr: "Tomorrow's Fajr",
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
        countdownEl.textContent = "Ended";
        return;
      }
      const days = Math.floor(diff / 86400000);
      const hours = Math.floor((diff % 86400000) / 3600000);
      const mins = Math.floor((diff % 3600000) / 60000);
      countdownEl.textContent = days + " days " + hours + " hours " + mins + " minutes";
    }
    updateCountdown();
    setInterval(updateCountdown, 60000);
  }

  // 3.5 Share Copy Button (Toast)
  document.querySelectorAll(".share-copy[data-copy-message]").forEach((btn) => {
    btn.addEventListener("click", function () {
      const msg = this.getAttribute("data-copy-message") || "Link copied!";
      if (navigator.clipboard) {
        navigator.clipboard.writeText(window.location.href).then(() => {
          if (typeof window.showToast === "function") window.showToast(msg);
          else alert(msg);
        });
      } else {
        if (typeof window.showToast === "function") window.showToast(msg);
        else alert(msg);
      }
    });
  });

  // 4. Book Archive - Price Range Slider
  var slider = document.getElementById("priceRangeSlider");
  var maxLabel = document.getElementById("priceMax");
  if (slider && maxLabel) {
    slider.addEventListener("input", function () {
      maxLabel.textContent = slider.value;
    });
  }

  // 5. & 6. Cart and Checkout pages use native WooCommerce shortcodes now.
  // Legacy manual rendering removed to avoid conflicts.

  // 7. Tab Filters (Dini Jiggasa / Notice)
  document.querySelectorAll(".jiggasa-tab").forEach((btn) => {
    btn.addEventListener("click", function () {
      const tab = this.dataset.tab;
      if (!tab) return;

      var tabContainer = this.closest(".jiggasa-tabs-container") || this.parentElement;
      tabContainer.querySelectorAll(".jiggasa-tab").forEach((b) => b.classList.remove("active"));
      this.classList.add("active");

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
      if (sbThumbs.length > 0 && sbThumbs[index]) {
        const newSrc = sbThumbs[index].getAttribute("data-src");
        if (newSrc) {
          sbLightboxImg.src = newSrc;
          currentImgIndex = index;
        }
      } else if (sbMainImg) {
        sbLightboxImg.src = sbMainImg.src;
      }
    };

    sbMainCover.addEventListener("click", function () {
      // Find current active thumb index
      if (sbThumbs.length > 0) {
        sbThumbs.forEach((thumb, idx) => {
          if (thumb.classList.contains("active")) {
            currentImgIndex = idx;
          }
        });
      }
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
      return String(str);
    };

    const updateLightbox = (index) => {
      const item = photoItems[index];
      const img = item.querySelector("img");
      const caption = item.getAttribute("data-caption");
      const date = item.getAttribute("data-date");
      const fullSrc = item.getAttribute("data-full"); // Get high quality image
      const thumbSrc = img.src.split("?")[0]; // Fallback to thumb if needed
      
      const src = fullSrc || thumbSrc;

      lightboxImg.src = src;
      lightboxCaption.innerHTML = `<strong>${caption}</strong><br><small style="opacity:0.7">${date}</small>`;
      lightboxCounter.textContent = `${index + 1} / ${photoItems.length}`;
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

  // 11. Probondho/Jiggasa Font Size Control
  const fontBtns = document.querySelectorAll(".probondho-font-btn");
  const articleBody = document.getElementById("articleBody");
  const jiggasaBody = document.getElementById("jiggasaAnswerBody");

  const applyFontSize = (container, val) => {
    if (!container) return;
    container.style.fontSize = val;
    // Apply explicitly to child elements to override deeply nested styles or WP block styles
    const children = container.querySelectorAll("*:not(.material-symbols-outlined)");
    children.forEach(el => {
      // Don't apply to specific structural elements if we don't want to break layout
      if(['SVG', 'svg', 'IMG', 'BR', 'HR', 'H1', 'H2', 'H3', 'H4', 'H5', 'H6'].includes(el.tagName)) return;
      el.style.setProperty("font-size", val, "important");
      el.style.setProperty("line-height", val === "21px" ? "2.2" : "1.8", "important");
    });
  };

  if (fontBtns.length > 0) {
    fontBtns.forEach((btn) => {
      btn.addEventListener("click", function () {
        fontBtns.forEach((b) => b.classList.remove("active"));
        this.classList.add("active");
        const size = this.dataset.size;
        const sizes = { small: "15px", medium: "17px", large: "21px" };
        const val = sizes[size] || "17px";
        
        applyFontSize(articleBody, val);
        applyFontSize(jiggasaBody, val);
      });
    });
  }

  // 12. Probondho TOC + Reading Progress
  const toc = document.getElementById("probondhoTOC");
  if (toc && articleBody) {
    const headings = articleBody.querySelectorAll("h2, h3");
    if (!headings.length) {
      const widget = toc.closest(".sidebar-widget");
      if (widget) widget.style.display = "none";
    } else {
      headings.forEach((h, i) => {
        if (!h.id) h.id = "toc-" + i;
        const a = document.createElement("a");
        a.href = "#" + h.id;
        a.className = h.tagName === "H3" ? "toc-sub-item" : "toc-main-item";
        a.textContent = h.textContent;
        toc.appendChild(a);
      });
    }
  }

  const progressFill = document.getElementById("readingProgressFill");
  if (progressFill && articleBody) {
    window.addEventListener("scroll", function () {
      const top = articleBody.getBoundingClientRect().top + window.scrollY;
      const height = articleBody.offsetHeight;
      const pct = Math.min(100, Math.max(0, ((window.scrollY - top) / height) * 100));
      progressFill.style.width = pct + "%";
    });
  }

  // 13. Book TOC Toggle
  const sbTocToggle = document.getElementById("sbTocToggle");
  const sbTocList = document.getElementById("sbTocList");
  if (sbTocToggle && sbTocList) {
    sbTocToggle.addEventListener("click", function () {
      sbTocList.classList.toggle("open");
      sbTocToggle.classList.toggle("active");
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
      toggleBtn.setAttribute("aria-label", "Open sub-menu");
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
  // Dynamic Video Modal Logic (Robust Fix for Error 153)
  const videoModal = document.getElementById("video-modal");
  const videoPlaceholder = document.getElementById("video-placeholder");
  const closeModal = document.querySelector(".close-modal");

  window.initVideoPlayers = function () {
    const videoThumbs = document.querySelectorAll(".video-thumb");
    const videoInplace = document.querySelectorAll(".video-inplace");

    if (videoThumbs.length > 0) {
      videoThumbs.forEach((thumb) => {
        if (thumb.dataset.bound === "1") return;
        thumb.dataset.bound = "1";
        thumb.addEventListener("click", function (e) {
          e.preventDefault();
          e.stopPropagation();
          const videoId = this.getAttribute("data-video-id");
          if (videoId && videoModal && videoPlaceholder) {
            const iframe = document.createElement("iframe");
            iframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0`;
            iframe.setAttribute("frameborder", "0");
            iframe.setAttribute("allowfullscreen", "true");
            iframe.setAttribute("allow", "autoplay; encrypted-media; picture-in-picture");
            videoPlaceholder.innerHTML = "";
            videoPlaceholder.appendChild(iframe);
            videoModal.classList.add("active");
            document.body.style.overflow = "hidden";
          }
        });
      });
    }

    if (videoInplace.length > 0) {
      videoInplace.forEach((wrapper) => {
        if (wrapper.dataset.bound === "1") return;
        wrapper.dataset.bound = "1";
        wrapper.addEventListener("click", function () {
          const videoId = this.getAttribute("data-video-id");
          if (videoId) {
            if (this.querySelector("iframe")) return;
            const iframe = document.createElement("iframe");
            iframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0`;
            iframe.setAttribute("frameborder", "0");
            iframe.setAttribute("allowfullscreen", "true");
            iframe.setAttribute("allow", "autoplay; encrypted-media; picture-in-picture");
            this.innerHTML = "";
            this.appendChild(iframe);
          }
        });
      });
    }
  };

  if (videoModal && videoPlaceholder) {
    window.initVideoPlayers();

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
      nextPrayerPrefix: "Next Prayer",
      locationPending: "Waiting for location permission...",
      locationDenied: "Location permission denied",
      locationUnavailable: "Location not supported in this browser",
      locationTimeout: "Location timeout, please try again",
      locationRetrying: "Retrying location...",
      loading: "Loading prayer times...",
      fetchError: "Could not load prayer times",
      retryHint: "Click the floating button to retry",
      prayerTimesTitle: "Today's Prayer Times",
      nextPrayerText: "Next Prayer",
      sehri: "Sehri Ends",
      iftar: "Iftar",
      tomorrowFajr: "Tomorrow's Fajr",
      am: "AM",
      pm: "PM",
      hijri: "Hijri",
      gps: "GPS"
    },
  };
  // Helper: convert English digits to Bengali ONLY when locale is bn_BD.
  // AM/PM and month names are always replaced from translated labels (already i18n'd via PHP).
  function toBanglaDigits(str) {
    const isBangla = (window.hidayahData && window.hidayahData.locale === 'bn_BD');
    const banglaDigits = (window.hidayahData && window.hidayahData.banglaDigits) ? window.hidayahData.banglaDigits : null;
    const labels = (prayerConfig && prayerConfig.labels) ? prayerConfig.labels : {};
    return String(str)
      .replace(/[0-9]/g, (match) => isBangla && banglaDigits ? banglaDigits[Number(match)] : match)
      .replace(/\bAM\b/g, labels.am || "AM")
      .replace(/\bPM\b/g, labels.pm || "PM")
      .replace(/\bGPS\b/g, labels.gps || "GPS")
      .replace(/\bHijri\b/g, labels.hijri || "Hijri");
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

    // Prayer names come from PHP via wp_localize_script (PRAYER_WIDGET_CONFIG.prayerNames)
    // so they are fully translatable via the .po / .mo language file.
    const prayerNames = Object.assign(
      { Fajr: "Fajr", Dhuhr: "Dhuhr", Asr: "Asr", Maghrib: "Maghrib", Isha: "Isha" },
      (prayerConfig.prayerNames || {})
    );

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
      const formatted = new Intl.DateTimeFormat("en-US", {
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
        new Intl.DateTimeFormat("en-US", {
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
          const hDay = toBanglaDigits(todayData.date.hijri.day);

          // Use month NUMBER (1-12) for Hijri lookup — API text varies (e.g. "Shawwāl" vs "Shawwal")
          const hMonthNum  = parseInt(todayData.date.hijri.month.number, 10);
          const hijriMonthsByNumber = [
            '', 'Muharram', 'Safar', "Rabi' al-awwal", "Rabi' al-thani",
            'Jumada al-ula', 'Jumada al-akhira', 'Rajab', "Sha'ban",
            'Ramadan', 'Shawwal', "Dhu al-Qi'dah", 'Dhu al-Hijjah'
          ];
          const hMonthKey = hijriMonthsByNumber[hMonthNum] || todayData.date.hijri.month.en;
          const hMonth = (prayerConfig.months && prayerConfig.months.Hijri && prayerConfig.months.Hijri[hMonthKey])
            ? prayerConfig.months.Hijri[hMonthKey]
            : hMonthKey;
          const hYear = toBanglaDigits(todayData.date.hijri.year);

          const gDay = toBanglaDigits(todayData.date.gregorian.day);
          // Normalize Gregorian month name (remove diacritics, trim) for robust lookup
          const gMonthRaw = todayData.date.gregorian.month.en || '';
          const gMonthNorm = gMonthRaw.normalize('NFD').replace(/[\u0300-\u036f]/g, '').trim();
          const gMonth = (prayerConfig.months && prayerConfig.months.Gregorian && prayerConfig.months.Gregorian[gMonthNorm])
            ? prayerConfig.months.Gregorian[gMonthNorm]
            : gMonthNorm;
          const gYear = toBanglaDigits(todayData.date.gregorian.year);

          const hijriLabel = prayerConfig.labels.hijri || "Hijri";
          readableDate = `${hDay} ${hMonth} ${hYear} ${hijriLabel}  •  ${gDay} ${gMonth} ${gYear}`;
        } else if (todayData.date && todayData.date.readable) {
          readableDate = toBanglaDigits(todayData.date.readable);
        } else {
          readableDate = toBanglaDigits(new Date().toLocaleDateString('en-US', { day: 'numeric', month: 'long', year: 'numeric' }));
        }

        prayerElements.sheetDate.textContent = readableDate;

        const hijriHeaderElement = document.getElementById("hijri-date");
        if (hijriHeaderElement) {
          hijriHeaderElement.textContent = readableDate;
        }

        if (prayerElements.sheetLocation.textContent === "GPS" || prayerElements.sheetLocation.textContent.startsWith("GPS:")) {
          try {
            const geoRes = await fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${latitude}&longitude=${longitude}&localityLanguage=en`);
            if (geoRes.ok) {
              const geoData = await geoRes.json();
              // Extract district name (adminLevel 5 = জেলা) from localityInfo
              let districtName = "";
              if (geoData.localityInfo && geoData.localityInfo.administrative) {
                const district = geoData.localityInfo.administrative.find((a) => a.adminLevel === 5);
                if (district && district.name) {
                  // Remove "District" suffix if present
                  let rawName = district.name.replace(/\s*District\s*$/, "").trim();
                  // Look up Bengali name
                  districtName = (prayerConfig.districts && prayerConfig.districts[rawName]) 
                    ? prayerConfig.districts[rawName] 
                    : rawName;
                }
              }
              const finalCity = districtName || geoData.city || geoData.locality || geoData.principalSubdivision;
              // Also try to translate city/locality if district lookup failed but city is in our list
              const translatedCity = (prayerConfig.districts && prayerConfig.districts[finalCity]) 
                ? prayerConfig.districts[finalCity] 
                : finalCity;

              prayerElements.sheetLocation.textContent = translatedCity || `GPS: ${toBanglaDigits(latitude.toFixed(4))}, ${toBanglaDigits(longitude.toFixed(4))}`;
            } else {
              prayerElements.sheetLocation.textContent = `GPS: ${toBanglaDigits(latitude.toFixed(4))}, ${toBanglaDigits(longitude.toFixed(4))}`;
            }
          } catch (e) {
            prayerElements.sheetLocation.textContent = `GPS: ${toBanglaDigits(latitude.toFixed(4))}, ${toBanglaDigits(longitude.toFixed(4))}`;
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
        prayerElements.sheetLocation.textContent = prayerConfig.fallbackCity || "Dhaka";
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

  // Legacy qty stepper logic removed. Native WooCommerce used instead.

  // Legacy add to cart listener removed. Native WooCommerce used instead.

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
          selectedYearText.textContent = year === "All Years" ? "By Year" : year;
        }
        yearDropdown.classList.remove("active");

        // Here you would typically trigger your filter logic
        console.log("Filtering by year:", year);
      });
    });
  }

  console.log("Website loaded successfully.");

  // ==========================================
  // Archive AJAX Helpers
  // ==========================================
  const ajaxUrl = window.hidayahData && hidayahData.ajaxUrl ? hidayahData.ajaxUrl : window.location.origin + "/wp-admin/admin-ajax.php";
  const ajaxNonce = window.hidayahData && hidayahData.nonce ? hidayahData.nonce : "";

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
        nonce: ajaxNonce,
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

      fetch(ajaxUrl, {
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
              self.$count.innerHTML = icon + ' Total ' + countText + ' Audios';
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

  // ==========================================
  // Book Archive AJAX Filtering
  // ==========================================
  const bookAjax = {
    $grid: document.getElementById('bookArchiveGrid'),
    $pagination: document.getElementById('bookPagination'),
    $loader: document.getElementById('bookLoader'),
    $count: document.getElementById('bookCountBadge'),

    $genre: document.getElementById('bookGenreFilter'),
    $author: document.getElementById('bookAuthorFilter'),
    $sort: document.getElementById('bookSortSelect'),
    $searchInput: document.getElementById('bookSearchInput'),
    $searchForm: document.getElementById('bookSearchForm'),

    init: function() {
      if (!this.$grid) return;

      const self = this;
      const triggers = [this.$genre, this.$author, this.$sort];
      triggers.forEach((el) => {
        if (el) el.addEventListener('change', () => self.filter(1));
      });

      if (this.$searchForm) {
        this.$searchForm.addEventListener('submit', (e) => {
          e.preventDefault();
          self.filter(1);
        });
      }

      document.addEventListener('click', (e) => {
        const link = e.target.closest('#bookPagination a');
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
      const data = new URLSearchParams({
        action: 'filter_book',
        nonce: ajaxNonce,
        genre: this.$genre ? this.$genre.value : '',
        author: this.$author ? this.$author.value : '',
        sort: this.$sort ? this.$sort.value : 'newest',
        search: this.$searchInput ? this.$searchInput.value : '',
        paged: paged
      });

      if (this.$loader) {
        this.$loader.style.display = 'flex';
        this.$grid.style.opacity = '0.5';
      }

      fetch(ajaxUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: data.toString()
      })
      .then(res => res.json())
      .then(res => {
        if (res.success) {
          const tempDiv = document.createElement('div');
          tempDiv.innerHTML = res.data.html;

          const paginationData = tempDiv.querySelector('.ajax-pagination-data');
          const countData = tempDiv.querySelector('.ajax-count-data');

          if (self.$pagination) {
            self.$pagination.innerHTML = paginationData ? paginationData.innerHTML : '';
          }

          if (self.$count && countData) {
            const countText = countData.textContent;
            const icon = self.$count.querySelector('.material-symbols-outlined').outerHTML;
            self.$count.innerHTML = icon + ' Total ' + countText + ' Books';
          }

          if (paginationData) paginationData.remove();
          if (countData) countData.remove();

          self.$grid.innerHTML = tempDiv.innerHTML;

          // Standard WooCommerce AJAX add to cart will handle buttons.

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

  bookAjax.init();

  // ==========================================
  // Dini Jiggasa Archive AJAX Filtering
  // ==========================================
  const jiggasaAjax = {
    $grid: document.getElementById('jiggasaList'),
    $pagination: document.getElementById('jiggasaPagination'),
    $loader: document.getElementById('jiggasaLoader'),
    $count: document.getElementById('jiggasaCountBadge'),

    $cat: document.getElementById('jiggasaCatFilter'),
    $sort: document.getElementById('jiggasaSortSelect'),
    $searchInput: document.getElementById('jiggasaSearchInput'),
    $searchForm: document.getElementById('jiggasaSearchForm'),
    $tabs: document.querySelectorAll('.jiggasa-tabs-container .jiggasa-tab[data-status]'),
    status: '',

    init: function() {
      if (!this.$grid) return;

      const self = this;
      const triggers = [this.$cat, this.$sort];
      triggers.forEach((el) => {
        if (el) el.addEventListener('change', () => self.filter(1));
      });

      if (this.$searchForm) {
        this.$searchForm.addEventListener('submit', (e) => {
          e.preventDefault();
          self.filter(1);
        });
      }

      this.$tabs.forEach((tab) => {
        tab.addEventListener('click', (e) => {
          e.preventDefault();
          self.$tabs.forEach((t) => t.classList.remove('active'));
          tab.classList.add('active');
          self.status = tab.dataset.status || '';
          self.filter(1);
        });
      });

      document.addEventListener('click', (e) => {
        const link = e.target.closest('#jiggasaPagination a');
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
      const data = new URLSearchParams({
        action: 'filter_jiggasa',
        nonce: ajaxNonce,
        cat: this.$cat ? this.$cat.value : '',
        sort: this.$sort ? this.$sort.value : 'newest',
        search: this.$searchInput ? this.$searchInput.value : '',
        status: this.status,
        paged: paged
      });

      if (this.$loader) {
        this.$loader.style.display = 'flex';
        this.$grid.style.opacity = '0.5';
      }

      fetch(ajaxUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: data.toString()
      })
      .then(res => res.json())
      .then(res => {
        if (res.success) {
          const tempDiv = document.createElement('div');
          tempDiv.innerHTML = res.data.html;

          const paginationData = tempDiv.querySelector('.ajax-pagination-data');
          const countData = tempDiv.querySelector('.ajax-count-data');

          if (self.$pagination) {
            self.$pagination.innerHTML = paginationData ? paginationData.innerHTML : '';
          }

          if (self.$count && countData) {
            const countText = countData.textContent;
            const icon = self.$count.querySelector('.material-symbols-outlined').outerHTML;
            self.$count.innerHTML = icon + ' Total ' + countText + ' Questions';
          }

          if (paginationData) paginationData.remove();
          if (countData) countData.remove();

          self.$grid.innerHTML = tempDiv.innerHTML;

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

  jiggasaAjax.init();

  // ==========================================
  // Monthly HD Archive AJAX Filtering
  // ==========================================
  const monthlyHdAjax = {
    $grid: document.getElementById('monthlyHdGrid'),
    $pagination: document.getElementById('monthlyHdPagination'),
    $loader: document.getElementById('monthlyHdLoader'),
    $count: document.getElementById('monthlyHdCountBadge'),

    $year: document.getElementById('monthlyHdYearFilter'),
    $category: document.getElementById('monthlyHdCategoryFilter'),
    $sort: document.getElementById('monthlyHdSortSelect'),
    $searchInput: document.getElementById('monthlyHdSearchInput'),
    $searchForm: document.getElementById('monthlyHdSearchForm'),

    init: function() {
      if (!this.$grid) return;

      const self = this;
      const triggers = [this.$year, this.$category, this.$sort];
      triggers.forEach((el) => {
        if (el) el.addEventListener('change', () => self.filter(1));
      });

      if (this.$searchForm) {
        this.$searchForm.addEventListener('submit', (e) => {
          e.preventDefault();
          self.filter(1);
        });
      }

      document.addEventListener('click', (e) => {
        const link = e.target.closest('#monthlyHdPagination a');
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
      const data = new URLSearchParams({
        action: 'filter_monthly_magazine',
        nonce: ajaxNonce,
        year: this.$year ? this.$year.value : '',
        category: this.$category ? this.$category.value : '',
        sort: this.$sort ? this.$sort.value : 'newest',
        search: this.$searchInput ? this.$searchInput.value : '',
        paged: paged
      });

      if (this.$loader) {
        this.$loader.style.display = 'flex';
        this.$grid.style.opacity = '0.5';
      }

      fetch(ajaxUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: data.toString()
      })
      .then(res => res.json())
      .then(res => {
        if (res.success) {
          const tempDiv = document.createElement('div');
          tempDiv.innerHTML = res.data.html;

          const paginationData = tempDiv.querySelector('.ajax-pagination-data');
          const countData = tempDiv.querySelector('.ajax-count-data');

          if (self.$pagination) {
            self.$pagination.innerHTML = paginationData ? paginationData.innerHTML : '';
          }

          if (self.$count && countData) {
            const countText = countData.textContent;
            const icon = self.$count.querySelector('.material-symbols-outlined').outerHTML;
            self.$count.innerHTML = icon + ' Total ' + countText + ' Issues';
          }

          if (paginationData) paginationData.remove();
          if (countData) countData.remove();

          self.$grid.innerHTML = tempDiv.innerHTML;

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

  monthlyHdAjax.init();

  // ==========================================
  // Notice Archive AJAX Filtering
  // ==========================================
  const noticeAjax = {
    $grid: document.getElementById('noticeList'),
    $pagination: document.getElementById('noticePagination'),
    $loader: document.getElementById('noticeLoader'),
    $count: document.getElementById('noticeCountBadge'),

    $cat: document.getElementById('noticeCatFilter'),
    $urgency: document.getElementById('noticeUrgencyFilter'),
    $sort: document.getElementById('noticeSortSelect'),
    $searchInput: document.getElementById('noticeSearchInput'),
    $searchForm: document.getElementById('noticeSearchForm'),
    $tabs: document.querySelectorAll('.jiggasa-tabs-container .jiggasa-tab[data-cat]'),
    cat: '',

    init: function() {
      if (!this.$grid) return;

      const self = this;
      const triggers = [this.$cat, this.$urgency, this.$sort];
      triggers.forEach((el) => {
        if (el) el.addEventListener('change', () => {
          if (el === self.$cat) self.cat = self.$cat.value || '';
          self.syncTabs();
          self.filter(1);
        });
      });

      if (this.$searchForm) {
        this.$searchForm.addEventListener('submit', (e) => {
          e.preventDefault();
          self.filter(1);
        });
      }

      this.$tabs.forEach((tab) => {
        tab.addEventListener('click', (e) => {
          e.preventDefault();
          self.$tabs.forEach((t) => t.classList.remove('active'));
          tab.classList.add('active');
          self.cat = tab.dataset.cat || '';
          if (self.$cat) self.$cat.value = self.cat;
          self.filter(1);
        });
      });

      document.addEventListener('click', (e) => {
        const link = e.target.closest('#noticePagination a');
        if (link) {
          e.preventDefault();
          const url = new URL(link.href);
          const paged = url.searchParams.get('paged') || 1;
          self.filter(paged);
        }
      });
    },

    syncTabs: function() {
      if (!this.$tabs.length) return;
      const target = this.cat || '';
      let matched = false;
      this.$tabs.forEach((tab) => {
        if (tab.dataset.cat === target) {
          matched = true;
          tab.classList.add('active');
        } else {
          tab.classList.remove('active');
        }
      });
      if (!matched) {
        this.$tabs.forEach((tab) => {
          if (!tab.dataset.cat) tab.classList.add('active');
        });
      }
    },

    filter: function(paged = 1) {
      const self = this;
      const data = new URLSearchParams({
        action: 'filter_notice',
        nonce: ajaxNonce,
        cat: this.cat || (this.$cat ? this.$cat.value : ''),
        urgency: this.$urgency ? this.$urgency.value : '',
        sort: this.$sort ? this.$sort.value : 'newest',
        search: this.$searchInput ? this.$searchInput.value : '',
        paged: paged
      });

      if (this.$loader) {
        this.$loader.style.display = 'flex';
        this.$grid.style.opacity = '0.5';
      }

      fetch(ajaxUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: data.toString()
      })
      .then(res => res.json())
      .then(res => {
        if (res.success) {
          const tempDiv = document.createElement('div');
          tempDiv.innerHTML = res.data.html;

          const paginationData = tempDiv.querySelector('.ajax-pagination-data');
          const countData = tempDiv.querySelector('.ajax-count-data');

          if (self.$pagination) {
            self.$pagination.innerHTML = paginationData ? paginationData.innerHTML : '';
          }

          if (self.$count && countData) {
            const countText = countData.textContent;
            const icon = self.$count.querySelector('.material-symbols-outlined').outerHTML;
            self.$count.innerHTML = icon + ' Total ' + countText + ' Notices';
          }

          if (paginationData) paginationData.remove();
          if (countData) countData.remove();

          self.$grid.innerHTML = tempDiv.innerHTML;

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

  noticeAjax.init();

  // ==========================================
  // Gallery Archive AJAX Filtering
  // ==========================================
  const galleryAjax = {
    $grid: document.getElementById('photoGalleryGrid'),
    $pagination: document.getElementById('galleryPagination'),
    $loader: document.getElementById('galleryLoader'),
    $count: document.getElementById('galleryCountBadge'),

    $cat: document.getElementById('galleryCatFilter'),
    $year: document.getElementById('galleryYearFilter'),
    $sort: document.getElementById('gallerySortSelect'),
    $searchInput: document.getElementById('gallerySearchInput'),
    $searchForm: document.getElementById('gallerySearchForm'),

    init: function() {
      if (!this.$grid) return;

      const self = this;
      const triggers = [this.$cat, this.$year, this.$sort];
      triggers.forEach((el) => {
        if (el) el.addEventListener('change', () => self.filter(1));
      });

      if (this.$searchForm) {
        this.$searchForm.addEventListener('submit', (e) => {
          e.preventDefault();
          self.filter(1);
        });
      }

      document.addEventListener('click', (e) => {
        const link = e.target.closest('#galleryPagination a');
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
      const data = new URLSearchParams({
        action: 'filter_gallery',
        nonce: ajaxNonce,
        cat: this.$cat ? this.$cat.value : '',
        year: this.$year ? this.$year.value : '',
        sort: this.$sort ? this.$sort.value : 'newest',
        search: this.$searchInput ? this.$searchInput.value : '',
        paged: paged
      });

      if (this.$loader) {
        this.$loader.style.display = 'flex';
        this.$grid.style.opacity = '0.5';
      }

      fetch(ajaxUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: data.toString()
      })
      .then(res => res.json())
      .then(res => {
        if (res.success) {
          const tempDiv = document.createElement('div');
          tempDiv.innerHTML = res.data.html;

          const paginationData = tempDiv.querySelector('.ajax-pagination-data');
          const countData = tempDiv.querySelector('.ajax-count-data');

          if (self.$pagination) {
            self.$pagination.innerHTML = paginationData ? paginationData.innerHTML : '';
          }

          if (self.$count && countData) {
            const countText = countData.textContent;
            const icon = self.$count.querySelector('.material-symbols-outlined').outerHTML;
            self.$count.innerHTML = icon + ' Total ' + countText + ' Albums';
          }

          if (paginationData) paginationData.remove();
          if (countData) countData.remove();

          self.$grid.innerHTML = tempDiv.innerHTML;

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

  galleryAjax.init();

  // ==========================================
  // Probondho Archive AJAX Filtering
  // ==========================================
  const probondhoAjax = {
    $grid: document.getElementById('probondhoArchiveList'),
    $pagination: document.getElementById('probondhoPagination'),
    $loader: document.getElementById('probondhoLoader'),
    $count: document.getElementById('probondhoCountBadge'),

    $cat: document.getElementById('probondhoCatFilter'),
    $sort: document.getElementById('probondhoSortSelect'),
    $searchInput: document.getElementById('probondhoSearchInput'),
    $searchForm: document.getElementById('probondhoSearchForm'),

    init: function() {
      if (!this.$grid) return;

      const self = this;
      const triggers = [this.$cat, this.$sort];
      triggers.forEach((el) => {
        if (el) el.addEventListener('change', () => self.filter(1));
      });

      if (this.$searchForm) {
        this.$searchForm.addEventListener('submit', (e) => {
          e.preventDefault();
          self.filter(1);
        });
      }

      document.addEventListener('click', (e) => {
        const link = e.target.closest('#probondhoPagination a');
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
      const data = new URLSearchParams({
        action: 'filter_probondho',
        nonce: ajaxNonce,
        cat: this.$cat ? this.$cat.value : '',
        sort: this.$sort ? this.$sort.value : 'newest',
        search: this.$searchInput ? this.$searchInput.value : '',
        paged: paged
      });

      if (this.$loader) {
        this.$loader.style.display = 'flex';
        this.$grid.style.opacity = '0.5';
      }

      fetch(ajaxUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: data.toString()
      })
      .then(res => res.json())
      .then(res => {
        if (res.success) {
          const tempDiv = document.createElement('div');
          tempDiv.innerHTML = res.data.html;

          const paginationData = tempDiv.querySelector('.ajax-pagination-data');
          const countData = tempDiv.querySelector('.ajax-count-data');

          if (self.$pagination) {
            self.$pagination.innerHTML = paginationData ? paginationData.innerHTML : '';
          }

          if (self.$count && countData) {
            const countText = countData.textContent;
            const icon = self.$count.querySelector('.material-symbols-outlined').outerHTML;
            self.$count.innerHTML = icon + ' Total ' + countText + ' Articles';
          }

          if (paginationData) paginationData.remove();
          if (countData) countData.remove();

          self.$grid.innerHTML = tempDiv.innerHTML;

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

  probondhoAjax.init();

  // ==========================================
  // Video Archive AJAX Filtering
  // ==========================================
  const videoAjax = {
    $grid: document.getElementById('videoArchiveGrid'),
    $pagination: document.getElementById('videoPagination'),
    $loader: document.getElementById('videoLoader'),
    $count: document.getElementById('videoCountBadge'),

    $topic: document.getElementById('videoTopicFilter'),
    $speaker: document.getElementById('videoSpeakerFilter'),
    $sort: document.getElementById('videoSortSelect'),
    $searchInput: document.getElementById('videoSearchInput'),
    $searchForm: document.getElementById('videoSearchForm'),

    init: function() {
      if (!this.$grid) return;

      const self = this;
      const triggers = [this.$topic, this.$speaker, this.$sort];
      triggers.forEach((el) => {
        if (el) el.addEventListener('change', () => self.filter(1));
      });

      if (this.$searchForm) {
        this.$searchForm.addEventListener('submit', (e) => {
          e.preventDefault();
          self.filter(1);
        });
      }

      document.addEventListener('click', (e) => {
        const link = e.target.closest('#videoPagination a');
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
      const data = new URLSearchParams({
        action: 'filter_video',
        nonce: ajaxNonce,
        topic: this.$topic ? this.$topic.value : '',
        speaker: this.$speaker ? this.$speaker.value : '',
        sort: this.$sort ? this.$sort.value : 'newest',
        search: this.$searchInput ? this.$searchInput.value : '',
        paged: paged
      });

      if (this.$loader) {
        this.$loader.style.display = 'flex';
        this.$grid.style.opacity = '0.5';
      }

      fetch(ajaxUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: data.toString()
      })
      .then(res => res.json())
      .then(res => {
        if (res.success) {
          const tempDiv = document.createElement('div');
          tempDiv.innerHTML = res.data.html;

          const paginationData = tempDiv.querySelector('.ajax-pagination-data');
          const countData = tempDiv.querySelector('.ajax-count-data');

          if (self.$pagination) {
            self.$pagination.innerHTML = paginationData ? paginationData.innerHTML : '';
          }

          if (self.$count && countData) {
            const countText = countData.textContent;
            const icon = self.$count.querySelector('.material-symbols-outlined').outerHTML;
            self.$count.innerHTML = icon + ' Total ' + countText + ' Videos';
          }

          if (paginationData) paginationData.remove();
          if (countData) countData.remove();

          self.$grid.innerHTML = tempDiv.innerHTML;

          if (typeof window.initVideoPlayers === 'function') {
            window.initVideoPlayers();
          }

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

  videoAjax.init();

  // ==========================================
  // Jiggasa Voting
  // ==========================================
  function initJiggasaVoting() {
    const voteButtons = document.querySelectorAll('.jiggasa-vote-btn');
    if (!voteButtons.length) return;

    voteButtons.forEach((btn) => {
      if (btn.dataset.bound === '1') return;
      btn.dataset.bound = '1';

      btn.addEventListener('click', (e) => {
        e.preventDefault();
        const postId = btn.getAttribute('data-id');
        const type = btn.getAttribute('data-type');
        if (!postId || !type) return;

        const data = new URLSearchParams({
          action: 'jiggasa_vote',
          nonce: ajaxNonce,
          post_id: postId,
          type: type
        });

        fetch(ajaxUrl, {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: data.toString()
        })
        .then(res => res.json())
        .then(res => {
          if (!res.success) {
            if (res.data && res.data.message === 'already_voted' && typeof window.showToast === 'function') {
              window.showToast('You have already voted.');
            }
            return;
          }

          const up = typeof res.data.up !== 'undefined' ? res.data.up : 0;
          const down = typeof res.data.down !== 'undefined' ? res.data.down : 0;
          const upBtn = document.querySelector('.jiggasa-vote-btn[data-type="up"]');
          const downBtn = document.querySelector('.jiggasa-vote-btn[data-type="down"]');

          if (upBtn) {
            upBtn.innerHTML = '<span class="material-symbols-outlined">thumb_up</span> Yes (' + toBanglaDigits(up) + ')';
          }
          if (downBtn) {
            downBtn.innerHTML = '<span class="material-symbols-outlined">thumb_down</span> No (' + toBanglaDigits(down) + ')';
          }
        })
        .catch(err => console.error('AJAX Error:', err));
      });
    });
  }

  initJiggasaVoting();

  // ==========================================
  // Monthly Download Count
  // ==========================================
  window.updateDownloadCount = function (postId) {
    if (!postId) return;

    const data = new URLSearchParams({
      action: 'update_dl_count',
      nonce: ajaxNonce,
      post_id: postId
    });

    fetch(ajaxUrl, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: data.toString()
    })
    .then(res => res.json())
    .catch(err => console.error('AJAX Error:', err));
  };

  // ==========================================
  // Single Book Star Rating Picker
  // ==========================================
  const starPicker = document.getElementById("sbStarPicker");
  if (starPicker) {
    const starBtns = starPicker.querySelectorAll(".sb-star-pick");
    const ratingInput = document.getElementById("hc-rating") || document.getElementById("rating");
    const ratingError = document.getElementById("rating-error");

    starBtns.forEach((btn, idx) => {
      btn.addEventListener("mouseover", () => {
        starBtns.forEach((b, i) => {
          if (i <= idx) {
            b.style.color = "#f59e0b";
            const s = b.querySelector("span");
            if (s) s.style.fontVariationSettings = '"FILL" 1, "wght" 400';
          } else {
            b.style.color = "#cbd5e1";
            const s = b.querySelector("span");
            if (s) s.style.fontVariationSettings = '"FILL" 0, "wght" 300';
          }
        });
      });

      btn.addEventListener("mouseout", () => {
        starBtns.forEach((b) => {
          b.style.color = "";
          const s = b.querySelector("span");
          if (s) {
            if (b.classList.contains("selected")) {
              s.style.fontVariationSettings = '"FILL" 1, "wght" 400';
            } else {
              s.style.fontVariationSettings = '"FILL" 0, "wght" 300';
            }
          }
        });
      });

      btn.addEventListener("click", () => {
        const val = parseInt(btn.getAttribute("data-val"));
        ratingInput.value = val;

        // Reset all stars and fill up to selected
        starBtns.forEach((b, i) => {
          const span = b.querySelector("span");
          if (i < val) {
            b.classList.add("selected");
            if (span) span.style.fontVariationSettings = '"FILL" 1, "wght" 400';
          } else {
            b.classList.remove("selected");
            if (span) span.style.fontVariationSettings = '"FILL" 0, "wght" 300';
          }
        });

        if (ratingError) ratingError.style.display = "none";
      });
    });

    const reviewForm = document.querySelector(".sb-review-form, .hc-form");
    if (reviewForm) {
      reviewForm.addEventListener("submit", (e) => {
        if (ratingInput && parseInt(ratingInput.value) === 0) {
          e.preventDefault();
          if (ratingError) ratingError.style.display = "block";
        }
      });
    }
  }
});
