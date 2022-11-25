<?php

namespace App\Controller;

use App\Entity\Ticket;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use \Doctrine\Common\Collections\Criteria;

/**
 * @Route("/api", name="api_")
 */
class TicketController extends AbstractController
{
    /**
     * @Route("/ticket/{id}", name="get_ticket", methods={"GET"})
     */
    public function getTicket(int $id, EntityManagerInterface $em): JsonResponse
    {
        //get ticket for a flight
        $ticket = $em->getRepository(Ticket::class)->find($id);

        if (!$ticket) {
            return $this->json(['message' => 'No ticket found for id: ' . $id], 404);
        }
        $data = $this->getAll($ticket);
        return $this->json($data, 200);
    }

    public function getAll($ticket)
    {
        return $data = [
            'ticket_id' => $ticket->getId(),
            'passport_id' => $ticket->getPassportId(),
            'source' => $ticket->getSource(),
            'destination' => $ticket->getDestination(),
            'departure_time' => $ticket->getDepartureTime(),
            'seat_number' => $ticket->getSeatNumber() 
        ];
    }

    /**
     * @Route("/ticket/cancel/{id}", name="cancel_ticket", methods={"GET"})
     */
    public function cancel(int $id, EntityManagerInterface $em): JsonResponse
    {
        //get ticket for a flight
        $ticket = $em->getRepository(Ticket::class)->find($id);

        if (!$ticket) {
            return $this->json(['message' => 'No ticket found for id: ' . $id], 404);
        }
        $em->remove($ticket);
        $em->flush();

        return $this->json([
            'message' => 'You ticket for id: '. $id .' is cancelled',
        ], 200);
    }

    /**
     * @Route("/ticket/changeSeat/{id}", name="change_seat", methods={"GET"})
     */
    public function changeSeat(int $id, EntityManagerInterface $em): JsonResponse
    {
        //get tickets for a flight
        $ticket = $em->getRepository(Ticket::class)->find($id);

        if (!$ticket) {
            return $this->json(['message' => 'No ticket found for id' . $id], 404);
        }

        $tickets = $em->getRepository(Ticket::class)->findBy([
            'source' => $ticket->getSource('source'),
            'destination' => $ticket->getDestination('destination'),
        ]);

        // if all tickets booked, return err msg
        if(count($tickets) > 31){
            return $this->json(['message' => 'All tickets are booked for this flight, You cannot change the seat'], 200);
        }

        //adding all booked tickets in array
        $already_tickets=[];
        foreach ($tickets as $tickt) {
            $already_tickets[] = $tickt->getSeatNumber();
        }

        //get the available ticket
        $seat_number='';
        $seat_arr=$this->getSeats();
        shuffle($seat_arr);
        foreach ($seat_arr as $value) {
            if(!in_array($value, $already_tickets)){
                $seat_number = $value;
                break;
            }
        }

        $ticket->setSeatNumber($seat_number);
        $em->flush();

        return $this->json([
            'message' => 'You seat number changed new seat number is '. $seat_number,
            'seat_number' => $seat_number
        ], 200);
    }

    private function getSeats(){
        $seat_arr=[];
        for ($tk=1; $tk < 33; $tk++) {
            $seat_arr[] = $tk;
        }
        return $seat_arr;
    }

    /**
     * @Route("/ticket/create", name="creat_ticket", methods={"POST"})
     */
    public function new(EntityManagerInterface $em, Request $request): JsonResponse
    {
        if($request->request->get('source') == '' || $request->request->get('destination') == '' || $request->request->get('passport_id') == ''){
            return $this->json(['message' => 'Source, destination or passport id cannot be empty']);
        }
        //get all tickets for a flight
        $tickets = $em->getRepository(Ticket::class)->findBy([
            'source' => $request->request->get('source'),
            'destination' => $request->request->get('destination')
        ]);
        if(count($tickets) > 0){
            // if all tickets booked, return err msg
            if(count($tickets) > 31){
                return $this->json(['message' => 'All tickets are booked for this flight'], 200);
            }

            //adding all booked tickets in array
            $already_tickets=[];
            foreach ($tickets as $ticket) {
                $already_tickets[] = $ticket->getSeatNumber();
            }

            //get the available ticket
            $seat_arr=$this->getSeats();
            foreach ($seat_arr as $value) {
                if(!in_array($value, $already_tickets)){
                    $seat_number = $value;
                    break;
                }
            }
        }else{
            $seat_number=1;
        }

        //create ticket
        $ticket = new Ticket();
        $ticket->setPassportId($request->request->get('passport_id'));
        $ticket->setSource($request->request->get('source'));
        $ticket->setDestination($request->request->get('destination'));
        $time_arr = ['00', '15', '30', '45'];
        $departure_time = rand(1,24) .':'. $time_arr[array_rand($time_arr)];
        $ticket->setDepartureTime($departure_time);
        $ticket->setSeatNumber($seat_number);

        $em->persist($ticket);
        $em->flush();

        $data = $this->getAll($ticket);

        return $this->json(['message' => 'Ticket created successfully. You ticketId is ' . $ticket->getId(), 'data' => $data], 200);        
 
    }
      
}
