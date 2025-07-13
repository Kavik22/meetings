import './Events.css';
import Link from 'next/link';
import EventsSection from './EventsSectionSize';

async function getEvents() {
    try {
        const response = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/v1/events`, {
            cache: 'no-store'
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error fetching events:', error);
        return [];
    }
}

export default async function Events() {
    const events = await getEvents();
    
    const formatDateTime = (dateTimeStr) => {
        const dateTime = new Date(dateTimeStr);
        const date = dateTime.toLocaleDateString('ru-RU', { day: 'numeric', month: 'numeric' });
        const time = dateTime.toLocaleTimeString('ru-RU', { hour: '2-digit', minute: '2-digit' });
        return { date, time };
    };

    // Функция для получения случайного угла поворота
    const getRandomRotation = () => {
        return Math.random() * 10 - 5; // Случайный угол от -5 до 5 градусов
    };

    // Вычисляем количество повторений фона
    const backgroundRepeatCount = Math.ceil(events.length / 2);

    return (
        <EventsSection events={events}>
            <div className="background-images-container">
                {Array.from({ length: backgroundRepeatCount }).map((_, index) => (
                    <img
                        key={index}
                        src="/carpet.png"
                        alt={`background ${index + 1}`}
                    />
                ))}
            </div>
            <div className='events-info'>
                <h2>Ивенты</h2>
                <p>Выбери какой-нибудь наш предстоящий ивент и присоединись, чтобы сделать пребывание на этой планете приятнее мне и себе</p>

            </div>
            <div className="container events-container">
                {events.length === 0 ? (
                    <p>Мероприятий не найдено</p>
                ) : (
                    events.map((event) => {
                        const { date, time } = formatDateTime(event.date_of_event);
                        return (
                            <Link href={`/events/${event.id}`} key={event.id}>
                                <div
                                    className="card"
                                    style={{ '--rotation': `${getRandomRotation()}deg` }}
                                >
                                    <img className='pin' src="/pin_3.png" alt="pin" />
                                    <img className="card-image" src={`${process.env.NEXT_PUBLIC_IMAGE_URL}/${event.image_path}`} alt={event.title} />
 
                                </div>
                            </Link>
                        );
                    })
                )}
            </div>
        </EventsSection>
    )
}
