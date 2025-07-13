import './Interships.css';
import Link from 'next/link';
import IntershipsSection from './IntershipsSection';


async function getInterships() {
    try {
        const response = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/v1/interships`, {
            cache: 'no-store'
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error fetching interships:', error);
        return [];
    }
}

export default async function Interships() {
    const interships = await getInterships();


    const formatDate = (dateStart, dateEnd) => {
        const dateStart_formated = new Date(dateStart).toLocaleDateString('ru-RU', { day: 'numeric', month: 'numeric' });
        const dateEnd_formated = new Date(dateEnd).toLocaleDateString('ru-RU', { day: 'numeric', month: 'numeric' });
        return { dateStart_formated, dateEnd_formated };
    };

    // Функция для получения случайного угла поворота
    const getRandomRotation = () => {
        return Math.random() * 10 - 5; // Случайный угол от -5 до 5 градусов
    };


    // Вычисляем количество повторений фона
    const backgroundRepeatCount = Math.ceil(interships.length / 2);


    return (
        <IntershipsSection interships={interships}>
            <div className="background-images-container">
                {Array.from({ length: backgroundRepeatCount }).map((_, index) => (
                    <img
                        key={index}
                        src="/bedrock_1.png"
                        alt={`background ${index + 1}`}
                    />
                ))}
            </div>
            <div className='interships-info'>
                <h2>Стажировки в России</h2>
                <p>Местечки, где радушные люди этой необъятной страны готовы принять тебя в свои объятья</p>
            </div>
            <div className="container interships-container">
                {interships.length === 0 ? (
                    <p>Стажировок не найдено</p>
                ) : (interships.map((intership) => {
                    const { dateStart_formated, dateEnd_formated } = formatDate(intership.date_start, intership.date_end);
                    return (
                        <Link 
                            href={intership.link_to_ru_site} 
                            key={intership.id}
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            <div key={intership.id} className="interships-card" style={{ '--rotation': `${getRandomRotation()}deg` }}>
                                <img className='pin_pixelart' src="/pin_2.png" alt="pin" />
                                <img className='card-image' src="/map_1.png" alt={intership.title} />
                                <div className='card-content'>
                                    <h3 className='card-title'>{intership.title}</h3>
                                    <p className='card-annotation'>{intership.annotation}</p>
                                    <p className='interships-card-date'>{dateStart_formated} - {dateEnd_formated}</p>

                                </div>
                            </div>
                        </Link>
                    )
                }))}
            </div>




        </IntershipsSection>
    )
}
