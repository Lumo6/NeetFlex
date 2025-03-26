import './ArtistDetails.css';
import { useEffect, useState } from 'react';

function ArtistDetails({ id, navigateTo }) {
  const API_BASE_URL = 'http://127.0.0.1:44444/api';
  const [artist, setArtist] = useState(null);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetch(`${API_BASE_URL}/artists/${id}`)
      .then((response) => {
        if (!response.ok) {
          throw new Error(
            `Erreur lors du chargement de l'artiste: ${response.statusText}`
          );
        }
        return response.json();
      })
      .then((data) => setArtist(data))
      .catch((err) => setError(err.message));
  }, [id]);

  if (error) return <p style={{ color: 'red' }}>{error}</p>;
  if (!artist) return <p>Chargement...</p>;

  return (
    <div className="artist-details-container">
      <h1>{artist.name}</h1>
      {artist.image && (
        <img
          src={`http://localhost:44444/uploads/artists/${artist.image}`}
          alt={artist.name}
          className="artist-image"
        />
      )}
      <p>{artist.desc}</p>
      <h2>Événements</h2>
      <ul className="artist-events-list">
        {artist.events && artist.events.length > 0 ? (
          artist.events.map((event) => (
            <li
              key={event.id}
              onClick={() => navigateTo('event', event.id)}
              className="artist-event-item"
            >
              {event.name} - {new Date(event.date).toLocaleDateString()}
            </li>
          ))
        ) : (
          <p>Aucun événement pour cet artiste.</p>
        )}
      </ul>
      <button onClick={() => navigateTo('artists')} className="back-button">
        Retour
      </button>
    </div>
  );
}

export default ArtistDetails;
