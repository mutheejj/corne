/* ===== UniElect — Custom JavaScript ===== */

document.addEventListener('DOMContentLoaded', function () {

    // ===== Mobile Menu Toggle =====
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuClose = document.getElementById('mobile-menu-close');
    const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');

    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function () {
            mobileMenu.classList.add('open');
            if (mobileMenuOverlay) mobileMenuOverlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
    }

    function closeMobileMenu() {
        if (mobileMenu) mobileMenu.classList.remove('open');
        if (mobileMenuOverlay) mobileMenuOverlay.classList.add('hidden');
        document.body.style.overflow = '';
    }

    if (mobileMenuClose) mobileMenuClose.addEventListener('click', closeMobileMenu);
    if (mobileMenuOverlay) mobileMenuOverlay.addEventListener('click', closeMobileMenu);

    // ===== Header Scroll Effect =====
    const header = document.getElementById('main-header');
    if (header) {
        let lastScroll = 0;
        window.addEventListener('scroll', function () {
            const currentScroll = window.pageYOffset;
            if (currentScroll > 50) {
                header.classList.add('shadow-2xl');
            } else {
                header.classList.remove('shadow-2xl');
            }
            lastScroll = currentScroll;
        });
    }

    // ===== Scroll Reveal Animations =====
    const revealElements = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale');

    const revealObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                revealObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    revealElements.forEach(function (el) {
        revealObserver.observe(el);
    });

    // ===== Counter Animation =====
    const counters = document.querySelectorAll('[data-counter]');
    const counterObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                const el = entry.target;
                const target = parseInt(el.getAttribute('data-counter'));
                const suffix = el.getAttribute('data-suffix') || '';
                const duration = 2000;
                const start = 0;
                const startTime = performance.now();

                function updateCounter(currentTime) {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    const easeOut = 1 - Math.pow(1 - progress, 3);
                    const current = Math.floor(easeOut * (target - start) + start);
                    el.textContent = current.toLocaleString() + suffix;
                    if (progress < 1) {
                        requestAnimationFrame(updateCounter);
                    } else {
                        el.textContent = target.toLocaleString() + suffix;
                    }
                }
                requestAnimationFrame(updateCounter);
                counterObserver.unobserve(el);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach(function (el) {
        counterObserver.observe(el);
    });

    // ===== Accordion / FAQ =====
    const accordionItems = document.querySelectorAll('[data-accordion]');
    accordionItems.forEach(function (item) {
        const trigger = item.querySelector('[data-accordion-trigger]');
        const content = item.querySelector('[data-accordion-content]');
        const icon = item.querySelector('[data-accordion-icon]');

        if (trigger && content) {
            trigger.addEventListener('click', function () {
                const isOpen = content.classList.contains('open');

                // Close all others
                document.querySelectorAll('[data-accordion-content]').forEach(function (c) {
                    c.classList.remove('open');
                });
                document.querySelectorAll('[data-accordion-icon]').forEach(function (i) {
                    i.style.transform = 'rotate(0deg)';
                });

                if (!isOpen) {
                    content.classList.add('open');
                    if (icon) icon.style.transform = 'rotate(180deg)';
                }
            });
        }
    });

    // ===== Password Toggle =====
    document.querySelectorAll('[data-password-toggle]').forEach(function (toggle) {
        toggle.addEventListener('click', function () {
            const input = document.querySelector(toggle.getAttribute('data-target'));
            const icon = toggle.querySelector('[data-password-icon]');
            if (input) {
                if (input.type === 'password') {
                    input.type = 'text';
                    if (icon) {
                        icon.innerHTML = '<path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/>';
                    }
                } else {
                    input.type = 'password';
                    if (icon) {
                        icon.innerHTML = '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>';
                    }
                }
            }
        });
    });

    // ===== Password Strength Indicator =====
    const passwordInput = document.getElementById('password');
    const strengthBar = document.getElementById('password-strength-bar');
    const strengthText = document.getElementById('password-strength-text');

    if (passwordInput && strengthBar) {
        passwordInput.addEventListener('input', function () {
            const value = passwordInput.value;
            let strength = 0;

            if (value.length >= 8) strength++;
            if (value.match(/[A-Z]/)) strength++;
            if (value.match(/[a-z]/)) strength++;
            if (value.match(/[0-9]/)) strength++;
            if (value.match(/[^A-Za-z0-9]/)) strength++;

            strengthBar.className = 'password-strength';

            if (value.length === 0) {
                strengthBar.style.width = '0';
                if (strengthText) strengthText.textContent = '';
            } else if (strength <= 2) {
                strengthBar.classList.add('strength-weak');
                if (strengthText) strengthText.textContent = 'Weak';
            } else if (strength === 3) {
                strengthBar.classList.add('strength-fair');
                if (strengthText) strengthText.textContent = 'Fair';
            } else if (strength === 4) {
                strengthBar.classList.add('strength-good');
                if (strengthText) strengthText.textContent = 'Good';
            } else {
                strengthBar.classList.add('strength-strong');
                if (strengthText) strengthText.textContent = 'Strong';
            }
        });
    }

    // ===== Tab Switching =====
    document.querySelectorAll('[data-tab-group]').forEach(function (tabGroup) {
        const tabs = tabGroup.querySelectorAll('[data-tab]');
        const panels = tabGroup.querySelectorAll('[data-tab-panel]');

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                const target = tab.getAttribute('data-tab');

                tabs.forEach(function (t) { t.classList.remove('active'); });
                panels.forEach(function (p) { p.classList.add('hidden'); });

                tab.classList.add('active');
                const panel = tabGroup.querySelector('[data-tab-panel="' + target + '"]');
                if (panel) panel.classList.remove('hidden');
            });
        });
    });

    // ===== Smooth Scroll for Anchor Links =====
    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href === '#') return;
            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // ===== Active Nav Link Based on URL =====
    const currentPath = window.location.pathname;
    document.querySelectorAll('.nav-link').forEach(function (link) {
        const href = link.getAttribute('href');
        if (href && (href === currentPath || (currentPath !== '/' && href && currentPath.startsWith(href)))) {
            link.classList.add('active');
        }
    });

    // ===== Back to Top Button =====
    const backToTop = document.getElementById('back-to-top');
    if (backToTop) {
        window.addEventListener('scroll', function () {
            if (window.pageYOffset > 300) {
                backToTop.classList.remove('opacity-0', 'pointer-events-none');
                backToTop.classList.add('opacity-100');
            } else {
                backToTop.classList.add('opacity-0', 'pointer-events-none');
                backToTop.classList.remove('opacity-100');
            }
        });
        backToTop.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // ===== Flash Message Auto-dismiss =====
    document.querySelectorAll('[data-flash]').forEach(function (flash) {
        setTimeout(function () {
            flash.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            flash.style.opacity = '0';
            flash.style.transform = 'translateY(-20px)';
            setTimeout(function () { flash.remove(); }, 500);
        }, 5000);
    });

    // ===== Dropdown Toggle =====
    document.querySelectorAll('[data-dropdown-toggle]').forEach(function (toggle) {
        toggle.addEventListener('click', function (e) {
            e.stopPropagation();
            const menu = document.querySelector(toggle.getAttribute('data-dropdown-toggle'));
            if (menu) {
                menu.classList.toggle('hidden');
            }
        });
    });

    document.addEventListener('click', function () {
        document.querySelectorAll('[data-dropdown-menu]').forEach(function (menu) {
            menu.classList.add('hidden');
        });
    });

    // ===== Form Validation Enhancement =====
    document.querySelectorAll('form[data-validate]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
            let valid = true;

            inputs.forEach(function (input) {
                if (!input.value.trim()) {
                    valid = false;
                    input.classList.add('border-red-500');
                    const errorMsg = input.parentElement.querySelector('[data-error]');
                    if (errorMsg) errorMsg.classList.remove('hidden');
                } else {
                    input.classList.remove('border-red-500');
                    const errorMsg = input.parentElement.querySelector('[data-error]');
                    if (errorMsg) errorMsg.classList.add('hidden');
                }
            });

            if (!valid) {
                e.preventDefault();
            }
        });
    });

    // ===== Student ID Format Helper =====
    const studentIdInput = document.getElementById('student_id');
    if (studentIdInput) {
        studentIdInput.addEventListener('input', function () {
            let value = this.value.toUpperCase().replace(/[^A-Z0-9-\/]/g, '');
            this.value = value;
        });
    }

    // ===== Parallax Effect =====
    const parallaxElements = document.querySelectorAll('[data-parallax]');
    if (parallaxElements.length > 0) {
        window.addEventListener('scroll', function () {
            const scrollY = window.pageYOffset;
            parallaxElements.forEach(function (el) {
                const speed = parseFloat(el.getAttribute('data-parallax')) || 0.3;
                el.style.transform = 'translateY(' + (scrollY * speed) + 'px)';
            });
        });
    }

    // ===== Animated Gradient Text on Scroll =====
    const gradientTexts = document.querySelectorAll('.gradient-text');
    const gradientObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.style.animationPlayState = 'running';
            }
        });
    });
    gradientTexts.forEach(function (el) { gradientObserver.observe(el); });

    // ===== Tilt Effect on Cards =====
    document.querySelectorAll('[data-tilt]').forEach(function (card) {
        card.addEventListener('mousemove', function (e) {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            const rotateX = ((y - centerY) / centerY) * -5;
            const rotateY = ((x - centerX) / centerX) * 5;
            card.style.transform = 'perspective(1000px) rotateX(' + rotateX + 'deg) rotateY(' + rotateY + 'deg) translateY(-6px)';
        });
        card.addEventListener('mouseleave', function () {
            card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateY(0)';
        });
    });

    // ===== Hero Background Slider =====
    var heroSlides = document.querySelectorAll('.hero-slide');
    var heroDots = document.querySelectorAll('.hero-dot');
    var currentSlide = 0;
    var heroInterval = null;

    function showHeroSlide(index) {
        heroSlides.forEach(function (slide, i) {
            if (i === index) {
                slide.classList.add('active');
            } else {
                slide.classList.remove('active');
            }
        });
        heroDots.forEach(function (dot, i) {
            if (i === index) {
                dot.classList.add('active');
            } else {
                dot.classList.remove('active');
            }
        });
        currentSlide = index;
    }

    function nextHeroSlide() {
        var next = (currentSlide + 1) % heroSlides.length;
        showHeroSlide(next);
    }

    if (heroSlides.length > 0) {
        heroInterval = setInterval(nextHeroSlide, 6000);

        heroDots.forEach(function (dot) {
            dot.addEventListener('click', function () {
                clearInterval(heroInterval);
                showHeroSlide(parseInt(this.getAttribute('data-slide')));
                heroInterval = setInterval(nextHeroSlide, 6000);
            });
        });
    }

    // ===== Countdown Timer =====
    document.querySelectorAll('[data-countdown]').forEach(function (el) {
        const targetDate = new Date(el.getAttribute('data-countdown')).getTime();

        function updateCountdown() {
            const now = new Date().getTime();
            const diff = targetDate - now;

            if (diff <= 0) {
                el.innerHTML = '<span class="text-orange-500">Voting is now open!</span>';
                return;
            }

            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            el.innerHTML =
                '<span class="font-bold text-2xl">' + days + 'd</span> ' +
                '<span class="font-bold text-2xl">' + hours + 'h</span> ' +
                '<span class="font-bold text-2xl">' + minutes + 'm</span> ' +
                '<span class="font-bold text-2xl">' + seconds + 's</span>';
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
    });

    // ===== Dashboard Sidebar Toggle (Mobile) =====
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const dashboardSidebar = document.querySelector('aside.w-64');
    const sidebarOverlay = document.getElementById('sidebar-overlay');

    if (sidebarToggle && dashboardSidebar) {
        sidebarToggle.addEventListener('click', function () {
            dashboardSidebar.classList.toggle('hidden');
            if (sidebarOverlay) sidebarOverlay.classList.toggle('hidden');
        });
    }
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function () {
            dashboardSidebar.classList.add('hidden');
            sidebarOverlay.classList.add('hidden');
        });
    }

    // ===== Notification Unread Count Polling =====
    const notificationBadge = document.querySelector('[data-notification-badge]');
    if (notificationBadge) {
        setInterval(function () {
            fetch('/notifications/unread-count')
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data.count > 0) {
                        notificationBadge.textContent = data.count > 99 ? '99+' : data.count;
                        notificationBadge.classList.remove('hidden');
                    } else {
                        notificationBadge.classList.add('hidden');
                    }
                })
                .catch(function () {});
        }, 60000);
    }

    // ===== Copy to Clipboard =====
    document.querySelectorAll('[data-copy]').forEach(function (el) {
        el.addEventListener('click', function () {
            const text = this.getAttribute('data-copy');
            navigator.clipboard.writeText(text).then(function () {
                const original = el.textContent;
                el.textContent = 'Copied!';
                setTimeout(function () { el.textContent = original; }, 2000);
            });
        });
    });

    // ===== Form Submit Loading State =====
    document.querySelectorAll('form[data-loading]').forEach(function (form) {
        form.addEventListener('submit', function () {
            const btn = form.querySelector('[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed');
                if (btn.dataset.loadingText) {
                    btn.textContent = btn.dataset.loadingText;
                }
            }
        });
    });

    // ===== Manifesto Character Counter =====
    const manifestoTextarea = document.getElementById('manifesto');
    const manifestoCounter = document.getElementById('manifesto-counter');
    if (manifestoTextarea && manifestoCounter) {
        function updateCounter() {
            var len = manifestoTextarea.value.length;
            manifestoCounter.textContent = len + ' characters';
            if (len < 100) {
                manifestoCounter.classList.add('text-red-500');
                manifestoCounter.classList.remove('text-green-500');
            } else {
                manifestoCounter.classList.add('text-green-500');
                manifestoCounter.classList.remove('text-red-500');
            }
        }
        manifestoTextarea.addEventListener('input', updateCounter);
        updateCounter();
    }

    // ===== File Upload Preview =====
    document.querySelectorAll('input[type="file"][data-preview]').forEach(function (input) {
        input.addEventListener('change', function () {
            const preview = document.querySelector(input.getAttribute('data-preview'));
            if (preview && this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) { preview.src = e.target.result; };
                reader.readAsDataURL(this.files[0]);
            }
        });
    });

    // ===== Ballot Timer =====
    const ballotTimer = document.getElementById('ballot-timer');
    if (ballotTimer) {
        const seconds = parseInt(ballotTimer.getAttribute('data-seconds'));
        let remaining = seconds;
        const interval = setInterval(function () {
            remaining--;
            if (remaining <= 0) {
                clearInterval(interval);
                ballotTimer.innerHTML = '<span class="text-red-600 font-bold">Time expired!</span>';
                const form = document.getElementById('ballot-form');
                if (form) form.submit();
            } else {
                var m = Math.floor(remaining / 60);
                var s = remaining % 60;
                ballotTimer.textContent = m + ':' + (s < 10 ? '0' : '') + s;
            }
        }, 1000);
    }
});
