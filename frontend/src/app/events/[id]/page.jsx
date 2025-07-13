import { notFound } from "next/navigation";
import EventDetailPage from "../../../Components/eventDetailPage/EventDetailPage.jsx";

export default async function Page({ params }) {
  const p = await params;
  const id = p.id;

  if (!id || !Number.isInteger(Number(id))) {
    return notFound();
  }

  return <EventDetailPage eventId={id} />;
}