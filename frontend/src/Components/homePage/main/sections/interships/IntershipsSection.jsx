'use client';

import { useEffect, useState } from 'react';

export default function IntershipsSection({ interships, children }) {
    const [sectionHeight, setSectionHeight] = useState(50 * interships.length);

    useEffect(() => {
        const calculateHeight = () => {
            if (window.innerWidth > 850) {
                return Math.ceil(interships.length / 3) * 50 + 20;
            }
            return 50 * interships.length + 20;
        };

        const updateHeight = () => {
            setSectionHeight(calculateHeight());
        };

        // Устанавливаем начальную высоту
        updateHeight();

        // Добавляем слушатель изменения размера окна
        window.addEventListener('resize', updateHeight);

        // Очищаем слушатель при размонтировании
        return () => window.removeEventListener('resize', updateHeight);
    }, [interships.length]);

    return (
        <section 
            className="section interships-section"
            style={{ height: `${sectionHeight}vh` }}
        >
            {children}
        </section>
    );
} 