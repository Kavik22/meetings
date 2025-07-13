'use client'

export default function Arrow() {
    const handleArrowClick = () => {
        window.scrollTo({
            top: window.innerHeight,
            behavior: 'smooth'
        });
    };
    
    return (
        <img 
        className="arrow" 
        src="/arrow.png" 
        alt="arrow" 
        onClick={handleArrowClick}
        style={{ cursor: 'pointer' }}
        />
    )
}

