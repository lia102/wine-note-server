import java.io.*;
import java.net.ServerSocket;
import java.net.Socket;
import java.util.HashMap;


public class TCPServer {
	static HashMap<String, Object> hash;
	public static void main(String[] args) {
		System.out.println("START SERVER");
		try {	
			ServerSocket server = new ServerSocket(8080);
			hash = new HashMap<String, Object>();
				while(true){
					Socket sck = server.accept();
					ChatThread chatThr = new ChatThread(sck, hash);
					chatThr.start();
				}
		} catch (IOException e) {
			e.printStackTrace();			
		}
		
 	}
}

class ChatThread extends Thread{
	Socket sck; 
	int id,otherId;
	PrintWriter pw;
	BufferedReader br;
	final HashMap<String, Object> hash;
	boolean initFlag = false;

	public ChatThread(Socket sck,HashMap<String, Object> hash) {
		this.sck = sck;
		this.hash = hash;
		try {
			pw = new PrintWriter(new OutputStreamWriter(sck.getOutputStream()));
			br = new BufferedReader(new InputStreamReader(sck.getInputStream()));
			initFlag = true;
		} catch (IOException e) {
			e.printStackTrace();
		}
	}
 @Override
	public void run() {
		String line = null;
		try {
			while((line = br.readLine()) != null) {
				if(line.split("/")[0].equals("myId")){
					id =  Integer.parseInt(line.split("/")[1]);
					synchronized (hash) {
						hash.put(String.valueOf(id), pw);
					}
					System.out.println("=================now user cnt : "+hash.size());
				}else if(line.split("/")[0].equals("readAlert")){
					System.out.println("readAlert : "+line);
					sendNotYetCnt(line);
				}else if(line.split("/")[0].equals("remove")){
					System.out.println("remove : "+line);
					removeMsg(line);
				}else if(line.split("/")[0].equals("newMemberCame")){
					System.out.println("newMemberCame : "+line);
					newMemberAlert(line);
				}else if(line.split("/")[0].equals("leaveChannel")){
					System.out.println("leaveChannel : "+line);
					newMemberAlert(line);
				}else if(line.split("/")[0].equals("handsUp")){
					System.out.println("handsUp : "+line);
					newMemberAlert(line);
				}else if(line.split("/")[0].equals("answerForHandsUp")){
					System.out.println("answerForHandsUp : "+line);
					newMemberAlert(line);
				}else if(line.split("/")[0].equals("inviteToSpeaker")){
					System.out.println("inviteToSpeaker : "+line);
					newMemberAlert(line);
				}else if(line.split("/")[0].equals("changeHost")){
					System.out.println("changeHost : "+line);
					newMemberAlert(line);
				}else if(line.split("/")[0].equals("changeToListener")){
					System.out.println("changeToListener : "+line);
					newMemberAlert(line);
				}else if(line.split("/")[0].equals("sendNotice")){
					System.out.println("sendNotice : "+line);
					newMemberAlert(line);
				}else{
					System.out.println("basicMsg : "+line);
					sendMsg(line);
				}
			}
		} catch (IOException e) {
		}finally { 
			synchronized (hash) {
				hash.remove(String.valueOf(id));
			}
			try {
				sck.close();
			} catch (IOException e) {
				System.out.println("Something Wrong");
			}
		}
	}
 	public void sendMsg(String msg) {
		synchronized (hash) {
			PrintWriter pw = null;
			pw = (PrintWriter) hash.get(msg.split("/")[1]);
			pw.println(msg);
			pw.flush();
			pw = (PrintWriter) hash.get(msg.split("/")[0]);
			pw.println(msg);
			pw.flush();
	    }
 	}
	 public void sendNotYetCnt(String msg) {
        synchronized (hash) {
            //msg.split("/")[0]=from,msg.split("/")[1]=to,msg.split("/")[2]=msg
            PrintWriter pw = null;
            pw = (PrintWriter) hash.get(msg.split("/")[2]); 
            pw.println(msg);
            pw.flush();
    
        }
    }
	public void newMemberAlert(String msg) {
        synchronized (hash) {
            //msg.split("/")[0]=from,msg.split("/")[1]=to,msg.split("/")[2]=msg
            PrintWriter pw = null;
            pw = (PrintWriter) hash.get(msg.split("/")[2]); 
            pw.println(msg);
            pw.flush();
    
        }
    }
	public void removeMsg(String msg) {
        synchronized (hash) {
            //msg.split("/")[0]=from,msg.split("/")[1]=to,msg.split("/")[2]=msg
            PrintWriter pw = null;
            pw = (PrintWriter) hash.get(msg.split("/")[2]);
			pw.println(msg);
			pw.flush();
			pw = (PrintWriter) hash.get(msg.split("/")[1]);
			pw.println(msg);
			pw.flush();
    
        }
    }
}
