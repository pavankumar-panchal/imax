<?php

if ($p_autoreceiptreconsilation <> 'yes') {
	$pagelink = getpagelink("unauthorised");
	include($pagelink);
} else {
	?>
	<link href="../style/main.css?dummy=<?php echo (rand()); ?>" rel=stylesheet>
	<link media="screen" rel="stylesheet" href="../style/colorbox.css?dummy=<?php echo (rand()); ?>" />
	<link media="screen" rel="stylesheet" href="../style/jquery.dataTables.min.css?dummy=<?php echo (rand()); ?>" />
	<script language="javascript" src="https://code.jquery.com/jquery-3.3.1.js?dummy=<?php echo (rand()); ?>"></script>
	<script language="javascript" src="../functions/jquery.dataTables.min.js?dummy=<?php echo (rand()); ?>"></script>
	<script language="javascript" src="../functions/autoreceiptreconcil.js?dummy=<?php echo (rand()); ?>"></script>

	<style>
		.progress {
			position: relative;
			width: 100%;
			border: 1px solid #ddd;
			padding: 1px;
			border-radius: 3px;
		}

		.bar {
			background-color: #B4F5B4;
			width: 0%;
			height: 20px;
			border-radius: 3px;
		}

		.percent {
			position: absolute;
			display: inline-block;
			top: 3px;
			left: 48%;
		}
	</style>


	<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
		<tr>
			<td width="77%" valign="top" style="border-bottom:#1f4f66 1px solid;">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
					<tr>
						<td valign="top">
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td>
										<table width="100%" border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td>
													<table width="100%" border="0" cellspacing="0" cellpadding="3">
														<tr>
															<td width="27%" class="active-leftnav">Upload</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<td>
													<table width="100%" border="0" cellspacing="0" cellpadding="0"
														style="border:1px solid #308ebc; border-top:none;">
														<tr>
															<td align="left" class="header-line" style="padding:0">
																&nbsp;&nbsp;Upload Only CSV Files</td>
															<td align="right" class="header-line" style="padding-right:7px">
															</td>
														</tr>
														<tr>
															<td colspan="2" valign="top">
																<div id="maindiv">
																	<form method="post" name="submitform" id="submitform"
																		enctype="multipart/form-data">
																		<table width="100%" border="0" cellspacing="0"
																			cellpadding="2">
																			<tr>
																				<td width="100%" valign="top"
																					style="border-right:1px solid #d1dceb;">
																					&nbsp;</td>
																			</tr>
																			<tr>
																				<td valign="top"
																					style="border-right:1px solid #d1dceb;">
																					<table width="40%" border="0"
																						cellspacing="0" cellpadding="3">
																						<tr bgcolor="#edf4ff">
																							<td width="10%" align="left"
																								valign="top">File Upload:
																							</td>
																							<td width="15%" align="left"
																								valign="top"><input
																									name="uploadfile"
																									type="file"
																									id="uploadfile"
																									size="30" maxlength="25"
																									required /></td>

																						</tr>
																						<tr bgcolor="#f7faff">
																							<td colspan="2" align="left"
																								valign="top"
																								style="padding:0">
																								<table width="100%"
																									border="0"
																									cellspacing="0"
																									cellpadding="0">
																									<tr>
																										<td>
																											<table
																												width="100%"
																												border="0"
																												cellspacing="0"
																												cellpadding="3">
																												<input
																													name="filter"
																													type="submit"
																													class="swiftchoicebutton-red"
																													id="filter"
																													value="Upload" />
																											</table>
																										</td>
																									</tr>
																								</table>
																							</td>
																						</tr>
																					</table>
																				</td>
																			</tr>
																		</table>
																	</form>
																</div>
															</td>
														</tr>
													</table>
													</form>
													<br>
													<br>

													<div class="progress">
														<div class="bar"></div>
														<div class="percent">0%</div>
													</div>

													<div id="status"></div>

												</td>
											</tr>

										</table>
									</td>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td>
							<table width="100%" border="0" cellspacing="0" cellpadding="0"
								style="border:1px solid #308ebc; border-top:none;">
								<tr class="header-line">
									<td width="131" align="left" style="padding:0">
										<div id="tabdescription">&nbsp; Receipt Details</div>
									</td>
									<td width="427" style="padding:0; text-align:center;">
										<span id="receiptcountid"></span>
									</td>
									<td width="35" align="left" style="padding:0">&nbsp;</td>
									<td width="139" align="left" style="padding:0">&nbsp;</td>
								</tr>
								<tr>
									<td colspan="4" align="center" valign="top">
										<div id="tabgroupgridc1" style="overflow:auto; height:200px; padding:2px;"
											align="center">
											<table width="100%" border="0" cellspacing="0" cellpadding="0">
												<tr>
													<td>
														<table width="100%" cellpadding="3" cellspacing="0">
															<tr>
																<td>
																	<div id="tabgroupgridc1_1" align="center"></div>
																</td>
															</tr>
															<tr>
																<td>
																	<div id="tabgroupgridc1link" align="left"
																		style="height:20px; padding:2px;"> </div>
																</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>

										</div>
										<div id="resultgrid"
											style="overflow:auto; display:none; height:150px; width:704px; padding:2px;"
											align="center">&nbsp;</div>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
<?php } ?>