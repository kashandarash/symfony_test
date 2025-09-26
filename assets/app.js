// Import global CSS.
import './styles/app.scss';

// Import Bootstrap.
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';

// Hover button js.
import { enableHoverButton } from './js/hover-button';
document.addEventListener("DOMContentLoaded", () => {
    const btn = document.querySelector(".moving-button");
    if (btn) {
        enableHoverButton(btn);
    }
});
