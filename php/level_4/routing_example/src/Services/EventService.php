<?php
declare(strict_types=1);

namespace App\Services;

use App\Domain\Event;
use App\Repositories\EventRepository;
use DateTime;
use RuntimeException;

class EventService
{
    public function __construct(private readonly EventRepository $repository) {}

    /**
     * Alle evenementen (voor het beheeroverzicht).
     *
     * @return Event[]
     */
    public function all(): array
    {
        return $this->repository->all();
    }

    /**
     * De aankomende evenementen (voor de publieke agenda).
     *
     * @return Event[]
     */
    public function upcoming(): array
    {
        return $this->repository->upcoming();
    }

    /**
     * Haalt één evenement op of gooit een fout als het niet bestaat.
     *
     * @throws RuntimeException als het evenement niet gevonden wordt
     */
    public function find(int $id): Event
    {
        $event = $this->repository->findById($id);

        if ($event === null) {
            throw new RuntimeException('Evenement niet gevonden.');
        }

        return $event;
    }

    /**
     * Valideert de invoer en maakt een nieuw evenement aan.
     *
     * @param array<string, string> $input ruwe formuliergegevens ($_POST)
     * @throws RuntimeException als de invoer niet klopt
     */
    public function create(array $input): void
    {
        $data = $this->validate($input);

        $this->repository->insert($data['title'], $data['description'], $data['location'], $data['date']);
    }

    /**
     * Valideert de invoer en werkt een bestaand evenement bij.
     *
     * @param array<string, string> $input ruwe formuliergegevens ($_POST)
     * @throws RuntimeException als het evenement niet bestaat of de invoer niet klopt
     */
    public function update(int $id, array $input): void
    {
        $this->find($id); // gooit als het evenement niet bestaat
        $data = $this->validate($input);

        $this->repository->update($id, $data['title'], $data['description'], $data['location'], $data['date']);
    }

    /**
     * Verwijdert een evenement.
     */
    public function delete(int $id): void
    {
        $this->repository->delete($id);
    }

    /**
     * Controleert de verplichte velden en geeft schoongemaakte gegevens terug.
     *
     * @param array<string, string> $input
     * @return array{title: string, description: string, location: string, date: string}
     * @throws RuntimeException bij een lege titel/locatie of een ongeldige datum
     */
    private function validate(array $input): array
    {
        $title       = trim($input['title'] ?? '');
        $description = trim($input['description'] ?? '');
        $location    = trim($input['location'] ?? '');
        $date        = trim($input['date'] ?? '');

        if ($title === '') {
            throw new RuntimeException('Titel is verplicht.');
        }

        if ($location === '') {
            throw new RuntimeException('Locatie is verplicht.');
        }

        if ($date === '' || !$this->isValidDate($date)) {
            throw new RuntimeException('Geef een geldige datum op (JJJJ-MM-DD).');
        }

        return [
            'title'       => $title,
            'description' => $description,
            'location'    => $location,
            'date'        => $date,
        ];
    }

    /**
     * Controleert of de datum echt het formaat JJJJ-MM-DD heeft.
     */
    private function isValidDate(string $date): bool
    {
        $parsed = DateTime::createFromFormat('Y-m-d', $date);

        return $parsed && $parsed->format('Y-m-d') === $date;
    }
}
