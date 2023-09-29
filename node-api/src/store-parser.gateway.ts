import {
  MessageBody,
  SubscribeMessage,
  WebSocketGateway,
  WebSocketServer,
  WsResponse,
} from '@nestjs/websockets';
import { from, Observable } from 'rxjs';
import { map } from 'rxjs/operators';
import { Server, Socket } from 'socket.io';

@WebSocketGateway({
  cors: {
    origin: '*',
  },
})
export class StoreParserGateway {
  @WebSocketServer()
  server: Server;

  handleConnection(client: Socket) {
    console.log('Connected');
  }

  handleDisconnect(client: Socket) {
    console.log('Disconnected');
    // Handle disconnection event
  }

  @SubscribeMessage('message')
  getMess(@MessageBody() data: any) {
    console.log(5555);
  }

  @SubscribeMessage('events')
  findAll(@MessageBody() data: any): Observable<WsResponse<number>> {
    console.log(data);
    return from([1, 2, 3]).pipe(
      map((item) => ({ event: 'events', data: item })),
    );
  }

  @SubscribeMessage('identity')
  async identity(@MessageBody() data: number): Promise<number> {
    console.log(data);
    return data;
  }
}
