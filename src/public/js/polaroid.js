// Gestion des polaroids flottants (drag, throw, collisions) pour login et register

(function() {
    // Vérifie la présence de polaroids sur la page
    const polaroids = Array.from(document.querySelectorAll('.floating-polaroid'));
    if (polaroids.length === 0) return;

    // Constantes partagées
    const POLAROID_W = 6.5, POLAROID_H = 9.5;
    const restitution = 0.8, friction = 0.995, minSpeed = 0.03;
    const minX = 0, maxX = 92, minY = 0, maxY = 88;
    const polaroidStates = new Map(), polaroidAnimFrames = new Map();

    function getPolaroidRect(state) {
        return { left: state.x, top: state.y, right: state.x + POLAROID_W, bottom: state.y + POLAROID_H };
    }
    function getLoginRect() {
        const login = document.querySelector('.relative.w-full.max-w-md.z-10');
        if (!login) return {left:9999,top:9999,right:9999,bottom:9999}; // fallback
        const rect = login.getBoundingClientRect();
        return {
            left: rect.left * 100 / window.innerWidth,
            top: rect.top * 100 / window.innerHeight,
            right: rect.right * 100 / window.innerWidth,
            bottom: rect.bottom * 100 / window.innerHeight
        };
    }
    function isColliding(a, b) {
        return !(a.right <= b.left || a.left >= b.right || a.bottom <= b.top || a.top >= b.bottom);
    }
    function axisOfMaxOverlap(r1, r2) {
        const overlapX = Math.min(r1.right, r2.right) - Math.max(r1.left, r2.left);
        const overlapY = Math.min(r1.bottom, r2.bottom) - Math.max(r1.top, r2.top);
        if (overlapX === overlapY) return Math.abs(r1.left - r2.left) > Math.abs(r1.top - r2.top) ? 'x' : 'y';
        return overlapX < overlapY ? 'x' : 'y';
    }
    function sign(x) { return x >= 0 ? 1 : -1; }
    function handleBorderBounce(state) {
        if (state.x < minX) { state.x = minX; state.vx = Math.abs(state.vx) * restitution; }
        if (state.x > maxX) { state.x = maxX; state.vx = -Math.abs(state.vx) * restitution; }
        if (state.y < minY) { state.y = minY; state.vy = Math.abs(state.vy) * restitution; }
        if (state.y > maxY) { state.y = maxY; state.vy = -Math.abs(state.vy) * restitution; }
    }
    function handleLoginBounce(state) {
        const loginRect = getLoginRect(), pRect = getPolaroidRect(state);
        if (!isColliding(pRect, loginRect)) return;
        const overlapX = Math.min(pRect.right, loginRect.right) - Math.max(pRect.left, loginRect.left);
        const overlapY = Math.min(pRect.bottom, loginRect.bottom) - Math.max(pRect.top, loginRect.top);
        if (overlapX > 0 && overlapY > 0 && Math.abs(overlapX - overlapY) < 1.5) {
            if (state.x + POLAROID_W / 2 < (loginRect.left + loginRect.right) / 2) {
                state.x = loginRect.left - POLAROID_W; state.vx = -Math.abs(state.vx) * restitution;
            } else { state.x = loginRect.right; state.vx = Math.abs(state.vx) * restitution; }
            if (state.y + POLAROID_H / 2 < (loginRect.top + loginRect.bottom) / 2) {
                state.y = loginRect.top - POLAROID_H; state.vy = -Math.abs(state.vy) * restitution;
            } else { state.y = loginRect.bottom; state.vy = Math.abs(state.vy) * restitution; }
        } else {
            const axis = axisOfMaxOverlap(pRect, loginRect);
            if (axis === 'x') {
                if (state.x + POLAROID_W / 2 < (loginRect.left + loginRect.right) / 2) {
                    state.x = loginRect.left - POLAROID_W; state.vx = -Math.abs(state.vx) * restitution;
                } else { state.x = loginRect.right; state.vx = Math.abs(state.vx) * restitution; }
            } else {
                if (state.y + POLAROID_H / 2 < (loginRect.top + loginRect.bottom) / 2) {
                    state.y = loginRect.top - POLAROID_H; state.vy = -Math.abs(state.vy) * restitution;
                } else { state.y = loginRect.bottom; state.vy = Math.abs(state.vy) * restitution; }
            }
        }
    }
    function handlePolaroidBounce(state, polaroid) {
        polaroids.forEach(function(other) {
            if (other === polaroid) return;
            let otherState = polaroidStates.get(other);
            let r1 = getPolaroidRect(state), r2 = getPolaroidRect(otherState);
            if (isColliding(r1, r2)) {
                const axis = axisOfMaxOverlap(r1, r2);
                if (axis === 'x') {
                    let tmp = state.vx;
                    state.vx = otherState.vx * restitution;
                    otherState.vx = tmp * restitution;
                    let mid = (r1.left + r1.right) / 2, omid = (r2.left + r2.right) / 2;
                    let overlap = (POLAROID_W - Math.abs(mid - omid));
                    if (overlap > 0) {
                        let sep = overlap / 2 * sign(mid - omid);
                        state.x += sep; otherState.x -= sep;
                    }
                } else if (axis === 'y') {
                    let tmp = state.vy;
                    state.vy = otherState.vy * restitution;
                    otherState.vy = tmp * restitution;
                    let mid = (r1.top + r1.bottom) / 2, omid = (r2.top + r2.bottom) / 2;
                    let overlap = (POLAROID_H - Math.abs(mid - omid));
                    if (overlap > 0) {
                        let sep = overlap / 2 * sign(mid - omid);
                        state.y += sep; otherState.y -= sep;
                    }
                }
            }
        });
    }
    function animatePolaroid(polaroid) {
        let state = polaroidStates.get(polaroid);
        function step() {
            state.x += state.vx; state.y += state.vy;
            state.vx *= friction; state.vy *= friction;
            handleBorderBounce(state);
            handleLoginBounce(state);
            handlePolaroidBounce(state, polaroid);
            polaroid.style.left = state.x + '%';
            polaroid.style.top = state.y + '%';
            if (Math.abs(state.vx) < minSpeed && Math.abs(state.vy) < minSpeed) {
                state.vx = 0; state.vy = 0;
                polaroidAnimFrames.set(polaroid, null); return;
            }
            let animFrame = requestAnimationFrame(step);
            polaroidAnimFrames.set(polaroid, animFrame);
        }
        let prev = polaroidAnimFrames.get(polaroid);
        if (prev) cancelAnimationFrame(prev);
        polaroidAnimFrames.set(polaroid, requestAnimationFrame(step));
    }

    // --- Drag & throw logic ---
    let draggingPolaroid = null, startX = 0, startY = 0, origX = 0, origY = 0, mouseX = 0, mouseY = 0;
    let velocityX = 0, velocityY = 0, dragHistory = [];
    polaroids.forEach((p) => {
        let x = parseFloat(p.style.left) || 10, y = parseFloat(p.style.top) || 20;
        polaroidStates.set(p, { x, y, vx: 0, vy: 0 });
        polaroidAnimFrames.set(p, null);
    });
    polaroids.forEach(function(polaroid) {
        polaroid.addEventListener('mousedown', function(e) {
            let animFrame = polaroidAnimFrames.get(polaroid);
            if (animFrame) cancelAnimationFrame(animFrame);
            polaroidAnimFrames.set(polaroid, null);
            draggingPolaroid = polaroid;
            polaroid.classList.add('dragging');
            startX = e.clientX; startY = e.clientY;
            origX = parseFloat(polaroid.style.left); origY = parseFloat(polaroid.style.top);
            mouseX = origX; mouseY = origY;
            dragHistory = [{x: startX, y: startY, t: performance.now()}];
            polaroid.style.animation = 'none';
            document.body.style.userSelect = 'none';
            e.preventDefault();
        });
    });
    document.addEventListener('mousemove', function(e) {
        if (!draggingPolaroid) return;
        let dx = e.clientX - startX, dy = e.clientY - startY;
        let newX = origX + dx * 100 / window.innerWidth, newY = origY + dy * 100 / window.innerHeight;
        newX = Math.max(minX, Math.min(maxX, newX)); newY = Math.max(minY, Math.min(maxY, newY));
        // Prevent overlap with login box
        const loginRect = getLoginRect();
        let tempRect = { left: newX, top: newY, right: newX + POLAROID_W, bottom: newY + POLAROID_H };
        if (isColliding(tempRect, loginRect)) {
            if (dx > 0 && tempRect.right > loginRect.left && tempRect.left < loginRect.left) newX = loginRect.left - POLAROID_W;
            if (dx < 0 && tempRect.left < loginRect.right && tempRect.right > loginRect.right) newX = loginRect.right;
            if (dy > 0 && tempRect.bottom > loginRect.top && tempRect.top < loginRect.top) newY = loginRect.top - POLAROID_H;
            if (dy < 0 && tempRect.top < loginRect.bottom && tempRect.bottom > loginRect.bottom) newY = loginRect.bottom;
        }
        // Prevent overlap with other polaroids
        for (let other of polaroids) {
            if (other === draggingPolaroid) continue;
            let otherState = polaroidStates.get(other);
            let otherRect = getPolaroidRect(otherState);
            let thisRect = { left: newX, top: newY, right: newX + POLAROID_W, bottom: newY + POLAROID_H };
            if (isColliding(thisRect, otherRect)) {
                if (dx > 0 && thisRect.right > otherRect.left && thisRect.left < otherRect.left) newX = otherRect.left - POLAROID_W;
                if (dx < 0 && thisRect.left < otherRect.right && thisRect.right > otherRect.right) newX = otherRect.right;
                if (dy > 0 && thisRect.bottom > otherRect.top && thisRect.top < otherRect.top) newY = otherRect.top - POLAROID_H;
                if (dy < 0 && thisRect.top < otherRect.bottom && thisRect.bottom > otherRect.bottom) newY = otherRect.bottom;
            }
        }
        mouseX = Math.max(minX, Math.min(maxX, newX));
        mouseY = Math.max(minY, Math.min(maxY, newY));
        draggingPolaroid.style.left = mouseX + '%';
        draggingPolaroid.style.top = mouseY + '%';
        // Ajoute à l'historique pour calculer la vélocité sur les 100 derniers ms
        const now = performance.now();
        dragHistory.push({x: e.clientX, y: e.clientY, t: now});
        while (dragHistory.length > 2 && now - dragHistory[0].t > 100) dragHistory.shift();
    });
    document.addEventListener('mouseup', function(e) {
        if (!draggingPolaroid) return;
        draggingPolaroid.classList.remove('dragging');
        document.body.style.userSelect = '';
        draggingPolaroid.style.animation = '';
        // Calcul de la vélocité sur les 100 derniers ms du drag
        let vdx = 0, vdy = 0, vdt = 1;
        if (dragHistory.length >= 2) {
            let first = dragHistory[0], last = dragHistory[dragHistory.length - 1];
            vdx = last.x - first.x; vdy = last.y - first.y; vdt = Math.max(1, last.t - first.t);
        }
        velocityX = (vdx * 100 / window.innerWidth) / vdt * 10;
        velocityY = (vdy * 100 / window.innerHeight) / vdt * 10;
        velocityX = Math.max(-2, Math.min(2, velocityX));
        velocityY = Math.max(-2, Math.min(2, velocityY));
        let state = polaroidStates.get(draggingPolaroid);
        state.x = mouseX; state.y = mouseY;
        state.vx = velocityX; state.vy = velocityY;
        animatePolaroid(draggingPolaroid);
        draggingPolaroid = null;
    });

    // On page load, start all polaroids with a random direction except polaroid 4 (texte ou vide)
    polaroids.forEach(function(polaroid) {
        let state = polaroidStates.get(polaroid);
        if (polaroid.querySelector('img') === null) {
            state.vx = 0; state.vy = 0;
            polaroidAnimFrames.set(polaroid, null);
        } else {
            state.vx = (Math.random() > 0.5 ? 0.5 : -0.5) * (0.5 + Math.random());
            state.vy = (Math.random() > 0.5 ? 0.5 : -0.5) * (0.5 + Math.random());
            animatePolaroid(polaroid);
        }
    });
})();
