import { useState, useEffect } from 'react';

import './App.css';

function App() {
  const API_BASE_URL = 'http://127.0.0.1:8000/api';
  const [artists, setArtists] = useState([]);
  const [events, setEvents] = useState([]);
  const [selectedArtist, setSelectedArtist] = useState(null);
  const [selectedEvent, setSelectedEvent] = useState(null);

  useEffect(() => {
    fetch(`${API_BASE_URL}/artists`)
      .then((response) => response.json())
      .then((data) => setArtists(data))
      .then(() => {
        console.log(artists);
      });

    fetch(`${API_BASE_URL}/events`)
      .then((response) => response.json())
      .then((data) => setEvents(data));
  }, []);

  const fetchArtist = (id) => {
    fetch(`${API_BASE_URL}/artists/${id}`)
      .then((response) => response.json())
      .then((data) => setSelectedArtist(data));
  };

  const fetchEvent = (id) => {
    fetch(`${API_BASE_URL}/events/${id}`)
      .then((response) => response.json())
      .then((data) => setSelectedEvent(data));
  };

  return (
    <div style={{ padding: '20px' }}>
      <script src="http://localhost:8097"></script>
      <h1>API Symfony - Artistes et Événements</h1>

      <h2>Liste des artistes</h2>
      <ul>
        {artists.map((artist) => (
          <li key={artist.id} onClick={() => fetchArtist(artist.id)}>
            {artist.name}
          </li>
        ))}
      </ul>
      {selectedArtist && (
        <div>
          <h3>Artiste sélectionné</h3>
          <p>{selectedArtist.name}</p>
        </div>
      )}

      <h2>Liste des événements</h2>
      <ul>
        {events.map((event) => (
          <li key={event.id} onClick={() => fetchEvent(event.id)}>
            {event.name}
          </li>
        ))}
      </ul>
      {selectedEvent && (
        <div>
          <h3>Événement sélectionné</h3>
          <p>{selectedEvent.name}</p>
        </div>
      )}
    </div>
  );
}

export default App;
