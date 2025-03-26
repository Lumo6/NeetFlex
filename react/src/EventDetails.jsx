import './EventDetails.css';
import { useEffect, useState } from 'react';

function EventDetails({ id, navigateTo }) {
  const API_BASE_URL = 'http://127.0.0.1:44444/api';
  const [event, setEvent] = useState(null);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetch(`${API_BASE_URL}/events/${id}`)
      .then((response) => {
        if (!response.ok) {
          throw new Error(
            `Erreur lors du chargement de l'événement: ${response.statusText}`
          );
        }
        return response.json();
      })
      .then((data) => setEvent(data))
      .catch((err) => setError(err.message));
  }, [id]);

  if (error) return <p style={{ color: 'red' }}>{error}</p>;
  if (!event) return <p>Chargement...</p>;

  return (
    <div className="event-details-container">
      <h1>{event.name}</h1>
      <p>Date: {new Date(event.date).toLocaleDateString()}</p>
      <p>
        <a
          className="clickable-artist"
          onClick={() => navigateTo('artist', event.artist.id)}
        >
          Artiste : {event.artist.name}
        </a>
      </p>
      <h2>Utilisateurs inscrits</h2>
      {event.users && event.users.length > 0 ? (
        <ul className="users-list">
          {event.users.map((user) => (
            <li key={user.id}>
              {user.name} ({user.email})
            </li>
          ))}
        </ul>
      ) : (
        <p>Aucun utilisateur inscrit pour cet événement.</p>
      )}
      <button onClick={() => navigateTo('home')} className="back-button">
        Retour
      </button>
    </div>
  );
}

export default EventDetails;
