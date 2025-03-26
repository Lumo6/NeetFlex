import { useEffect, useState } from 'react';

function Home({ navigateTo }) {
  const API_BASE_URL = 'http://127.0.0.1:44444/api';
  const [artists, setArtists] = useState([]);
  const [events, setEvents] = useState([]);

  useEffect(() => {
    fetch(`${API_BASE_URL}/artists`)
      .then((response) => response.json())
      .then((data) => setArtists(data));

    fetch(`${API_BASE_URL}/events`)
      .then((response) => response.json())
      .then((data) => setEvents(data));
  }, []);

  return (
    <div style={{ padding: '20px', textAlign: 'center' }}>
      <h1>Artistes et Événements</h1>
      <h2>Liste des artistes</h2>
      <ul style={{ listStyleType: 'none', textAlign: 'center' }}>
        {artists.map((artist) => (
          <li key={artist.id}>{artist.name}</li>
        ))}
      </ul>

      <h2>Liste des événements</h2>
      <ul style={{ listStyleType: 'none', textAlign: 'center' }}>
        {events.map((event) => (
          <li key={event.id}>{event.name}</li>
        ))}
      </ul>
    </div>
  );
}

export default Home;
