package org.econtent;

import java.sql.Connection;
import java.sql.SQLException;

import org.API.OverDrive.IOverDriveAPIServices;
import org.API.OverDrive.IOverDriveAPIUtils;
import org.API.OverDrive.IOverDriveCollectionIterator;
import org.API.OverDrive.OverDriveAPIUtils;
import org.apache.solr.common.SolrInputDocument;
import org.json.simple.JSONArray;
import org.json.simple.JSONObject;
import org.solr.ISolrUtils;
import org.solr.ISolrWrapper;
import org.solr.SolrUtils;
import org.vufind.ProcessorResults;

import db.DBeContentRecordServices;
import db.IDBeContentRecordServices;

public class PopulateSolrOverDriveAPIItems
{

	private IOverDriveCollectionIterator overDriveAPICollectionIterator;
	private IDBeContentRecordServices eContentRecordDAO;
	private IOverDriveAPIUtils overDriveAPIUtils;
	private ISolrWrapper solrWrapper;
	private IOverDriveAPIServices overDriveApiServices;
	@SuppressWarnings("unused")
	private ISolrUtils solrUtils;
	ProcessorResults processorResults = null;
	
	public PopulateSolrOverDriveAPIItems(IOverDriveCollectionIterator overDriveCollectionIterator,
			 Connection conn, 
			 ISolrWrapper solrWrapper,
			 IOverDriveAPIServices overDriveApiServices,
			 ProcessorResults processorResults)
	{
		this(
				overDriveCollectionIterator,
				new DBeContentRecordServices(conn),
				new OverDriveAPIUtils(),
				solrWrapper,
				new SolrUtils(),
				overDriveApiServices
			);
		this.processorResults = processorResults;
	}
	
	public PopulateSolrOverDriveAPIItems(IOverDriveCollectionIterator overDriveCollectionIterator,
										 Connection conn, 
										 ISolrWrapper solrWrapper,
										 IOverDriveAPIServices overDriveApiServices)
	{
		this(
				overDriveCollectionIterator,
				new DBeContentRecordServices(conn),
				new OverDriveAPIUtils(),
				solrWrapper,
				new SolrUtils(),
				overDriveApiServices
			);
	}

	public PopulateSolrOverDriveAPIItems(IOverDriveCollectionIterator overDriveAPICollectionIterator,
										 IDBeContentRecordServices eContentRecordDAO,
										 IOverDriveAPIUtils overDriveAPIUtilsMock,
										 ISolrWrapper solrWrapper,
										 ISolrUtils solrUtils,
										 IOverDriveAPIServices overDriveApiServices)
	{
		this.overDriveAPICollectionIterator = overDriveAPICollectionIterator;
		this.eContentRecordDAO = eContentRecordDAO;
		this.overDriveAPIUtils = overDriveAPIUtilsMock;
		this.solrWrapper = solrWrapper;
		this.solrUtils = solrUtils;
		this.overDriveApiServices = overDriveApiServices;
	}
	
	public void addNote(String note)
	{
		if(this.processorResults!=null)
		{
			this.processorResults.addNote(note);
		}
	}

	public void execute() throws SQLException 
	{
		this.addNote("Started getting OverDrive API Collection");
		int j = 0;
		long totalItems;
	
		while (this.overDriveAPICollectionIterator.hasNext())
		{
			JSONObject resultsDC = this.overDriveAPICollectionIterator.next();
			JSONArray items = (JSONArray) resultsDC.get("products");
			
			totalItems = (Long)resultsDC.get("totalItems");
			
			
			for (int i = 0; i < items.size(); i++) 
			{
				j++;
				if(this.processorResults!=null)
				{
					System.out.print("\rProcessing OverDrive API Item: " + j + "/" + totalItems);
				}
				
				JSONObject item = (JSONObject) items.get(i);
				
				String overDriveId = (String) item.get("id");
				Boolean dbExists = this.eContentRecordDAO.existOverDriveRecord((String) item.get("id"),"OverDrive");
				if(dbExists)
				{
					String recordId = this.eContentRecordDAO.selectRecordIdByOverDriveIdBySource(overDriveId, "OverDriveAPI");
					if(recordId != null)
					{
						this.addNote("Deleting OverDrive API Item because it is now on Marc File. ID: " + overDriveId);
						String solrDocumentId = "econtentRecord" + recordId;
						this.solrWrapper.deleteDocumentById(solrDocumentId);
						this.eContentRecordDAO.deleteRecordById(recordId);
					}
				}
				else
				{
					String recordId = this.eContentRecordDAO.selectRecordIdByOverDriveIdBySource(overDriveId, "OverDriveAPI");
					JSONObject itemMetadata = this.overDriveApiServices.getItemMetadata(overDriveId);
					if(recordId == null)
					{
						this.addNote("New OverDrive API Item" + overDriveId);
						this.eContentRecordDAO.addOverDriveAPIItem(itemMetadata);
						recordId = this.eContentRecordDAO.selectRecordIdByOverDriveIdBySource(overDriveId, "OverDriveAPI");
					}
					else
					{
						try
						{
							this.eContentRecordDAO.updateOverDriveAPIItem(recordId, itemMetadata);	
						}
						catch (Exception e) {
							this.addNote("Error Updating  " + overDriveId + "API Item to the Database: " + e.getMessage());
						}
						
					}
					SolrInputDocument document = this.overDriveAPIUtils.getSolrInputDocumentFromDigitalCollectionItem(recordId, itemMetadata);
					this.solrWrapper.addDocument(document);
			 	}
			}
		}
		
		this.addNote("Added " + j + " new items from OverDrive API");
		this.addNote("Finished getting OverDrive API Collection");
	}
}