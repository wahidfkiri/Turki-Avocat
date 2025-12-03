<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssignedNotification extends Notification
{
    use Queueable;

    public $task;

    /**
     * Create a new notification instance.
     */
    public function __construct($task)
    {
        $this->task = $task;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $taskDetails = [
            'Titre' => $this->task->titre,
            'Description' => $this->task->description,
            'Date de début' => $this->task->date_debut ? $this->task->date_debut->format('d/m/Y') : 'Non définie',
            'Date de fin' => $this->task->date_fin ? $this->task->date_fin->format('d/m/Y') : 'Non définie',
            'Priorité' => $this->getPriorityText($this->task->priorite),
            'Statut' => $this->getStatusText($this->task->statut),
            'Dossier' => $this->task->dossier ? $this->task->dossier->numero_dossier : 'Non spécifié',
        ];

        return (new MailMessage)
            ->subject('Nouvelle Tâche Assignée - ' . $this->task->titre)
            ->view('emails.task-assigned', [
                'task' => $this->task,
                'taskDetails' => $taskDetails,
                'user' => $notifiable,
                'hasFile' => $this->task->hasFile(),
                'note' => $this->task->note,
            ]);
    }

    /**
     * Convert priority code to text
     */
    private function getPriorityText($priority)
    {
        $priorities = [
            'basse' => 'Basse',
            'normale' => 'Normale',
            'haute' => 'Haute',
            'urgente' => 'Urgente',
        ];

        return $priorities[$priority] ?? 'Non définie';
    }

    /**
     * Convert status code to text
     */
    private function getStatusText($status)
    {
        $statuses = [
            'a_faire' => 'En attente',
            'en_cours' => 'En cours',
            'terminee' => 'Terminée',
            'en_retard' => 'En retard',
        ];

        return $statuses[$status] ?? 'Inconnu';
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'task_title' => $this->task->titre,
            'assigned_to' => $notifiable->id,
            'assigned_by' => $this->task->user ? $this->task->user->id : null,
        ];
    }
}