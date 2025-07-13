'use client';

import { useState } from 'react';
import Link from 'next/link';

export default function EventForm({ eventId, initialCount }) {
    const [participantsCount, setParticipantsCount] = useState(initialCount);
    const [formData, setFormData] = useState({
        name: "",
        tag: "",
        email: "",
        found_out_about_us: "",
        other_source: "",
        direction_of_study: "",
    });

    const [aproveSubmit, setAproveSubmit] = useState(false);
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [error, setError] = useState(null);
    const [success, setSuccess] = useState(false);

    const handleChange = (event) => {
        setFormData({
            ...formData,
            [event.target.name]: event.target.value,
        });
    }

    const handleSubmit = async (event) => {
        event.preventDefault();
        setIsSubmitting(true);
        setError(null);

        if (formData.found_out_about_us !== "Other") {
            formData.other_source = "";
        }

        try {
            const response = await fetch(`${process.env.NEXT_PUBLIC_IMAGE_URL}/api/v1/events/${eventId}/participants/add`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'include',
                body: JSON.stringify(formData)
            });

            if (!response.ok) {
                throw new Error('Ошибка при отправке формы');
            }

            const data = await response.json();
            setSuccess(true);
            setParticipantsCount(count => count + 1);
        } catch (err) {
            console.error('Ошибка:', err);
            setError(err.message);
        } finally {
            setIsSubmitting(false);
        }
    }

    const options = [
        { id: "vk", value: "VK", label: "Группа AIESEC в Екатеринбурге в ВК" },
        { id: "tg", value: "TG", label: "ТГ канал" },
        { id: "friend", value: "Friend", label: "От знакомого из AIESEC" },
        { id: "past", value: "PastEvent", label: "Был на прошлых мероприятиях от AIESEC" },
        { id: "other", value: "Other", label: "Другое" }
    ];

    return (
        <div className="event-form">
            <p className="event-detail-item">
                <span>👥</span> Количество зарегистрированных участников: {participantsCount}
            </p>
            {success ? (
                <div className="success-message">Данные приняты, буду ждать тебя на мероприятии!</div>
            ) : (
                <form onSubmit={handleSubmit}>
                    <div>
                        <input type="text" name="name" placeholder="Как к тебе обращаться?" onChange={handleChange} required />
                    </div>
                    <div>
                        <input type="email" name="email" placeholder="Твой email" onChange={handleChange} required />
                    </div>
                    <div>
                        <input type="text" name="tag" placeholder="Твой ник тг или ссылка на ВК" onChange={handleChange} required />
                    </div>

                    <div className="found_out_about_us">
                        <label htmlFor="found_out_about_us">Откуда ты узнал о мероприятии?</label>
                        <div className="options-container">
                            {options.map((option) => (
                                <div
                                    key={option.id}
                                    className={`option ${formData.found_out_about_us === option.value ? 'selected' : ''}`}
                                    onClick={() => handleChange({ target: { name: 'found_out_about_us', value: option.value } })}
                                >
                                    {option.label}
                                </div>
                            ))}
                        </div>

                        {formData.found_out_about_us === "Other" && (
                            <input type="text" name="other_source" placeholder="Укажи источник" value={formData.other_source} onChange={handleChange} required />
                        )}
                    </div>

                    <div>
                        <input type="text" name="direction_of_study" placeholder="На каком направлении учишься?" onChange={handleChange} required />
                    </div>

                    <div className="aprove">
                        <input type="checkbox" id="aprove" onChange={() => setAproveSubmit(!aproveSubmit)} required />
                        <label htmlFor="aprove">В соответствии с требованиями Федерального закона РФ «О персональных данных» №152-ФЗ от 27.07.2006 даю своё согласие на обработку своих персональных данных.</label>
                    </div>

                    <button type="submit" disabled={!aproveSubmit || isSubmitting}>
                        {isSubmitting ? 'Отправка...' : 'Хочу придти'}
                    </button>
                    {error && <div className="error-message">{error}</div>}
                </form>
            )}
            <Link href="/" className="back-link">Fuck go back</Link>
        </div>
    );
} 