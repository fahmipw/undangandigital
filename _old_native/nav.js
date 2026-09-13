/* ============================================
   SPA Controller - Undangan Pernikahan Digital
   Elvy & Rokim — Single Page Application
   ============================================ */

(function () {
  "use strict";

  /* =============================================
     COVER GATE
     ============================================= */
  function initCoverGate() {
    var gate = document.getElementById("opening-overlay");
    var btn = document.getElementById("btn-buka");
    if (!gate || !btn) return;

    btn.addEventListener("click", function () {
      // Button press animation
      btn.style.transform = "scale(0.95)";
      setTimeout(function () {
        btn.style.transform = "";
      }, 150);

      // Open the gate
      setTimeout(function () {
        gate.classList.add("opened");
        document.body.classList.remove("no-scroll");

        // Show header, dot nav, scroll progress
        setTimeout(function () {
          var header = document.querySelector(".top-header");
          var dotNav = document.getElementById("dot-nav");
          var scrollProg = document.getElementById("scroll-progress");

          if (header) header.classList.add("visible");
          if (dotNav) dotNav.classList.add("active");
          if (scrollProg) scrollProg.classList.add("active");

          // Start music
          if (musicConfig.autoplay) {
            startMusic(0);
          }

          // Trigger first visible reveals
          initScrollReveal();
        }, 400);

        // Remove gate from DOM after transition
        setTimeout(function () {
          gate.style.display = "none";
        }, 1200);
      }, 200);
    });
  }

  /* =============================================
     AUDIO / MUSIC
     ============================================= */
  var bgMusic = null;
  var musicPlaying = false;
  var musicConfig = {
    file_path: "lagu.mp3",
    volume: 0.5,
    autoplay: true
  };

  // Make music variables globally accessible
  window.bgMusic = bgMusic;
  window.musicPlaying = musicPlaying;

  // Load music configuration
  function loadMusicConfig() {
    const invId = window.invId || 1; // Get from global variable set in index.php
    fetch(`api/music.php?inv_id=${invId}`)
      .then(response => response.json())
      .then(data => {
        if (data.success && data.music) {
          musicConfig = data.music;
          initializeMusic();
        } else {
          // Fallback to default
          initializeMusic();
        }
      })
      .catch(() => {
        // Fallback to default
        initializeMusic();
      });
  }

  function initializeMusic() {
    if (bgMusic) {
      bgMusic.pause();
      bgMusic = null;
    }
    
    bgMusic = new Audio(musicConfig.file_path);
    bgMusic.loop = true;
    bgMusic.volume = musicConfig.volume;
    
    // Update global reference
    window.bgMusic = bgMusic;
    
    // Auto start music if enabled and cover gate is opened
    if (musicConfig.autoplay && document.body.classList.contains('no-scroll') === false) {
      startMusic(0);
    }
  }

  function fadeInAudio(audio, targetVol) {
    audio.volume = 0;
    var steps = 20;
    var stepTime = 500 / steps;
    var stepVol = targetVol / steps;
    var timer = setInterval(function () {
      audio.volume = Math.min(targetVol, audio.volume + stepVol);
      if (audio.volume >= targetVol) clearInterval(timer);
    }, stepTime);
  }

  function startMusic(fromTime) {
    if (!bgMusic) return;
    
    bgMusic.currentTime = fromTime || 0;
    bgMusic.volume = 0;
    bgMusic.play().then(function () {
      musicPlaying = true;
      window.musicPlaying = true; // Update global reference
      updateMusicUI(true);
      fadeInAudio(bgMusic, musicConfig.volume);
    }).catch(function () {
      bgMusic.volume = musicConfig.volume;
      // Will retry on first user interaction
      document.addEventListener("click", function retryPlay() {
        if (!musicPlaying) {
          startMusic(0);
        }
        document.removeEventListener("click", retryPlay);
      }, { once: true });
    });
  }

  function updateMusicUI(isPlaying) {
    var btn = document.getElementById("music-toggle");
    if (!btn) return;
    var icon = btn.querySelector(".material-symbols-outlined");
    var ping = btn.querySelector(".ping-ring");
    if (isPlaying) {
      icon.textContent = "pause";
      if (ping) ping.style.display = "block";
      btn.style.animation = "spinSlow 3s linear infinite";
    } else {
      icon.textContent = "music_note";
      if (ping) ping.style.display = "none";
      btn.style.animation = "none";
    }
  }

  // Make updateMusicUI globally accessible
  window.updateMusicUI = updateMusicUI;

  function initMusicToggle() {
    var btn = document.getElementById("music-toggle");
    if (!btn) return;

    btn.addEventListener("click", function () {
      if (!bgMusic) return;
      
      if (!musicPlaying) {
        startMusic(bgMusic.currentTime);
      } else {
        bgMusic.pause();
        musicPlaying = false;
        window.musicPlaying = false; // Update global reference
        updateMusicUI(false);
      }
    });
  }

  /* =============================================
     DOT NAVIGATION
     ============================================= */
  /* =============================================
     DOT NAVIGATION WITH ICONS
     ============================================= */
  var DOT_SECTIONS = [
    { id: "mempelai", label: "Mempelai", icon: "favorite" },
    { id: "gallery", label: "Galeri", icon: "photo_library" },
    { id: "kisah", label: "Kisah", icon: "auto_stories" },
    { id: "quotes", label: "Quotes", icon: "format_quote" },
    { id: "acara", label: "Acara", icon: "event" },
    { id: "rsvp", label: "RSVP", icon: "how_to_reg" },
    { id: "gift", label: "Gift", icon: "featured_seasonal_and_gifts" },
    { id: "guestbook", label: "Ucapan", icon: "chat" }
  ];

  function buildDotNav() {
    var nav = document.getElementById("dot-nav");
    if (!nav) return;

    var track = document.createElement("div");
    track.className = "dot-track";

    DOT_SECTIONS.forEach(function (sec, i) {
      var item = document.createElement("div");
      item.className = "dot-item";
      item.dataset.target = sec.id;

      var icon = document.createElement("span");
      icon.className = "material-symbols-outlined dot-icon";
      icon.textContent = sec.icon;

      var label = document.createElement("span");
      label.className = "dot-label";
      label.textContent = sec.label;

      item.appendChild(icon);
      item.appendChild(label);

      item.addEventListener("click", function () {
        var target = document.getElementById(sec.id);
        if (target) {
          target.scrollIntoView({ behavior: "smooth", block: "start" });
        }
      });

      track.appendChild(item);
    });

    nav.appendChild(track);
  }

  function initHeaderMenu() {
    var menuItems = document.querySelectorAll(".header-menu a");
    menuItems.forEach(function (item) {
      item.addEventListener("click", function (e) {
        e.preventDefault();
        var targetId = this.getAttribute("href").substring(1);
        var target = document.getElementById(targetId);
        if (target) {
          target.scrollIntoView({ behavior: "smooth", block: "start" });
        }
      });
    });
  }

  function initDotObserver() {
    var dots = document.querySelectorAll(".dot-item");
    if (!dots.length) return;

    var sectionEls = DOT_SECTIONS.map(function (sec) {
      return document.getElementById(sec.id);
    }).filter(Boolean);

    var currentActive = null;

    var observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          // Detect when a section is sufficiently visible in the middle of the screen
          if (entry.isIntersecting) {
            var id = entry.target.id;
            if (currentActive === id) return;
            currentActive = id;

            // Update dots
            dots.forEach(function (dot) {
              if (dot.dataset.target === id) {
                dot.classList.add("active");
              } else {
                dot.classList.remove("active");
              }
            });

            // Update header menu (if any)
            var menuItems = document.querySelectorAll(".header-menu a");
            menuItems.forEach(function (item) {
              if (item.dataset.section === id) {
                item.classList.add("active");
              } else {
                item.classList.remove("active");
              }
            });
          }
        });
      },
      {
        // Observe when the section crosses the 40% - 60% viewport area
        threshold: [0, 0.1, 0.2, 0.3],
        rootMargin: "-30% 0px -40% 0px"
      }
    );

    sectionEls.forEach(function (el) {
      observer.observe(el);
    });
  }

  /* =============================================
     SCROLL REVEAL (IntersectionObserver)
     ============================================= */
  function initScrollReveal() {
    var elements = document.querySelectorAll(
      ".reveal, .reveal-scale, .reveal-left, .reveal-right, .reveal-stagger"
    );
    if (!elements.length) return;

    var observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add("visible");
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.12, rootMargin: "0px 0px -30px 0px" }
    );

    elements.forEach(function (el) {
      observer.observe(el);
    });
  }

  /* =============================================
     SCROLL PROGRESS BAR
     ============================================= */
  function initScrollProgress() {
    var bar = document.getElementById("scroll-progress");
    if (!bar) return;

    window.addEventListener("scroll", function () {
      var scrollTop = window.scrollY || document.documentElement.scrollTop;
      var scrollHeight = document.documentElement.scrollHeight - window.innerHeight;
      if (scrollHeight > 0) {
        var percent = (scrollTop / scrollHeight) * 100;
        bar.style.width = percent + "%";
      }
    }, { passive: true });
  }

  /* =============================================
     LIVE COUNTDOWN
     ============================================= */
  function initCountdown() {
    var el = document.getElementById("countdown");
    if (!el) return;

    // Get reception date from global variable set in index.php (from database settings)
    // Format: "2026-06-11 11:00" (reception_date + reception_time_start)
    var receptionDateStr = window.receptionDateStr || "2026-06-11 11:00";
    
    // Parse the date string and add UTC+7 timezone
    var receptionDate = new Date(receptionDateStr.replace(" ", "T") + ":00+07:00").getTime();

    function pad(n) { return n < 10 ? "0" + n : String(n); }

    function update() {
      var now = Date.now();
      var diff = receptionDate - now;

      if (diff <= 0) {
        el.innerHTML =
          '<div class="countdown-item"><span class="countdown-number">00</span><span class="countdown-label">Hari</span></div>' +
          '<span class="countdown-sep">:</span>' +
          '<div class="countdown-item"><span class="countdown-number">00</span><span class="countdown-label">Jam</span></div>' +
          '<span class="countdown-sep">:</span>' +
          '<div class="countdown-item"><span class="countdown-number">00</span><span class="countdown-label">Menit</span></div>' +
          '<span class="countdown-sep">:</span>' +
          '<div class="countdown-item"><span class="countdown-number">00</span><span class="countdown-label">Detik</span></div>';
        return;
      }

      var days = Math.floor(diff / 86400000); diff %= 86400000;
      var hours = Math.floor(diff / 3600000); diff %= 3600000;
      var mins = Math.floor(diff / 60000); diff %= 60000;
      var secs = Math.floor(diff / 1000);

      el.innerHTML =
        '<div class="countdown-item"><span class="countdown-number">' + pad(days) + '</span><span class="countdown-label">Hari</span></div>' +
        '<span class="countdown-sep">:</span>' +
        '<div class="countdown-item"><span class="countdown-number">' + pad(hours) + '</span><span class="countdown-label">Jam</span></div>' +
        '<span class="countdown-sep">:</span>' +
        '<div class="countdown-item"><span class="countdown-number">' + pad(mins) + '</span><span class="countdown-label">Menit</span></div>' +
        '<span class="countdown-sep">:</span>' +
        '<div class="countdown-item"><span class="countdown-number">' + pad(secs) + '</span><span class="countdown-label">Detik</span></div>';
    }

    update();
    setInterval(update, 1000);
  }

  /* =============================================
     PARALLAX - Subtle effect on hero backgrounds
     ============================================= */
  function initParallax() {
    var parallaxEls = document.querySelectorAll("[data-parallax]");
    if (!parallaxEls.length) return;

    window.addEventListener("scroll", function () {
      var scrollY = window.scrollY;
      parallaxEls.forEach(function (el) {
        var speed = parseFloat(el.dataset.parallax) || 0.3;
        el.style.transform = "translateY(" + (scrollY * speed) + "px)";
      });
    }, { passive: true });
  }

  /* =============================================
     SCROLL TO TOP ON PAGE LOAD/REFRESH
     ============================================= */
  function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  /* =============================================
     INIT
     ============================================= */
  document.addEventListener("DOMContentLoaded", function () {
    // Scroll to top on page load/refresh
    scrollToTop();

    initCoverGate();
    buildDotNav();
    initHeaderMenu();
    loadMusicConfig(); // Load music configuration first
    initMusicToggle();
    initCountdown();
    initScrollProgress();
    initParallax();

    // Dot observer and scroll reveal start after gate opens
    // (called from initCoverGate after transition)
    // But also set up dot observer in case
    setTimeout(function () {
      initDotObserver();
    }, 500);
  });

})();
