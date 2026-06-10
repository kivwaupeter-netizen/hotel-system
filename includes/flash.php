<?php
function setFlash($type, $message) {
    $_SESSION['flash'] = [
        'type'    => $type,
        'message' => $message
    ];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function displayFlash() {
    $flash = getFlash();
    if ($flash !== null) {
        echo '<div class="flash-message flash-' . htmlspecialchars($flash['type']) . '">';
        echo htmlspecialchars($flash['message']);
        echo '</div>';
        echo '<script>
            setTimeout(function() {
                var el = document.querySelector(".flash-message");
                if (el) {
                    el.style.transition = "opacity 0.5s ease";
                    el.style.opacity = "0";
                    setTimeout(function() {
                        el.style.display = "none";
                    }, 500);
                }
            }, 4000);
        </script>';
    }
}