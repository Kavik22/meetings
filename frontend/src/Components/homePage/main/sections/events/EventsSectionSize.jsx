'use client';

import { useEffect, useState } from 'react';

export default function EventsSection({ events, children }) {
    const [sectionHeight, setSectionHeight] = useState(50 * events.length);

    useEffect(() => {
        const calculateHeight = () => {
            if (window.innerWidth > 850) {
                return Math.ceil(events.length / 3) * 50 + 20;
            }
            return 50 * events.length + 20;
        };

        const updateHeight = () => {
            setSectionHeight(calculateHeight());
        };

        
        updateHeight();

        
        window.addEventListener('resize', updateHeight);

        
        return () => window.removeEventListener('resize', updateHeight);
    }, [events.length]);

    return (
        <section 
            className="section events-section"
            style={{ height: `${sectionHeight}vh` }}
        >
            {children}
        </section>
    );
} 