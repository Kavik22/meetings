import './Footer.css';

export default function Footer() {
    return (
        <footer>
            <img className='metall' src="metall.png" alt="metall" />
            <div className='info'>
                <p>Международные стажировки ниже</p>
            </div>
            <div className='malachite-box'>
                <img className='footer-image' src="footer.png" alt="footer" />
                <div className="hologram-trapezoid">
                    <a href="https://aiesec.org/search" target="_blank" rel="noopener noreferrer">
                        <div className="hologram-text">Pick me!</div>
                    </a>
                </div>
            </div>
        </footer>
    )
}
