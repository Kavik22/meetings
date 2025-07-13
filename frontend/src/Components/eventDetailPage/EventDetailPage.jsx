import EventForm from './EventForm.jsx';
import './EventDetailPage.css';

async function getEvent(eventId) {
    try {
        const response = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/api/v1/events/${eventId}`, {
            cache: 'no-store',
            headers: {
                'Access-Control-Allow-Origin': process.env.NEXT_PUBLIC_API_URL,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error fetching event:', error);
        return null;
    }
}

export default async function EventDetailPage({ eventId }) {
    const event = await getEvent(eventId);

    if (!event) {
        return <div className="event-detail-container">
            <p>Мероприятие не найдено</p>
        </div>;
    }

    // Format date and time
    const eventDate = new Date(event.date_of_event);
    const formattedDate = eventDate.toLocaleDateString('ru-RU', { day: 'numeric', month: 'long' });
    const formattedTime = eventDate.toLocaleTimeString('ru-RU', { hour: '2-digit', minute: '2-digit' });

    return (
        <div className='event-section'>
            <div className="event-detail-container flex-col gap-4">
                <div className="event-content">
                    <div className='event-card'>
                        <div className="event-info">
                            <h1 className="event-title">{event.title}</h1>
                            <img src={`${process.env.NEXT_PUBLIC_IMAGE_URL}/${event.image_path}`} alt={event.title} className="event-image" />
                            <p className="event-description">{event.description}</p>
                            <div className="event-details">
                                <p className="event-detail-item">
                                    <span>📅</span> {formattedDate} в {formattedTime}
                                </p>
                                <p className="event-detail-item">
                                    <span>📍</span> {event.address}
                                </p>
                            </div>
                        </div>
                    </div>
                    <EventForm eventId={eventId} initialCount={event.participants.length || 0} />
                </div>
            </div >
        </div>
    );
}
