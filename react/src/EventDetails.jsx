import './EventDetails.css'; // Add this line to import the CSS file
import { useEffect, useState } from 'react';

function EventDetails({ id, navigateTo }) {
  const API_BASE_URL = 'http://127.0.0.1:44444/api';
  const [event, setEvent] = useState(null);

  useEffect(() => {
    fetch(`${API_BASE_URL}/events/${id}`)
      .then((response) => response.json())
      .then((data) => {
        console.log(data);
        setEvent(data);
      });
  }, [id]);

  if (!event) return <p>Chargement...</p>;

  console.log(event);

  return (
    <div className="event-details-container">
      <h1>{event.name}</h1>
      <p>Date: {new Date(event.date).toLocaleDateString()}</p>
      <p>Artiste: {event.artist.name}</p>
      <button onClick={() => navigateTo('home')} className="back-button">
        Retour
      </button>
    </div>
  );
}

export default EventDetails;
