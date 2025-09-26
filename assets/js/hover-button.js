import '../styles/hover-button.scss';

export function enableHoverButton(button) {
    let hoverTimer;

    button.addEventListener("mouseenter", () => {
        hoverTimer = setTimeout(() => {
            // pick a random direction
            const dx = (Math.random() - 0.5) * 100; // -50 to +50px
            const dy = (Math.random() - 0.5) * 100;

            // move the button
            button.style.transform = `translate(${dx}px, ${dy}px)`;

            // shrink text
            button.classList.add("small-text");
        }, 2000); // 2 seconds
    });

    button.addEventListener("mouseleave", () => {
        clearTimeout(hoverTimer);

        // reset on leave
        button.style.transform = "translate(0,0)";
        button.classList.remove("small-text");
    });
}